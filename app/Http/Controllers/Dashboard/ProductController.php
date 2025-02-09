<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variant', 'createdBy', 'updatedBy']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $from = Carbon::parse($request->input('from_date'))->startOfDay();
            $to = Carbon::parse($request->input('to_date'))->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);

        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $variants = Variant::orderBy('name')->get();
        return view('dashboard.products.input', compact('categories', 'variants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:1024'],
            'category_id' => ['required', 'exists:categories,id'],
            'variant_id' => ['required', 'exists:variants,id'],
            'code' => ['unique:products,code'], // Mencegah kode duplikat
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('products') : null;
        $productCode = $this->generateNoproduct($request);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'variant_id' => $request->variant_id,
            'code' => $productCode,
            'created_by' => Auth::id(),
        ]);

        return redirect('/barang')->with('message', 'Data Successfully Added');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $variants = Variant::orderBy('name')->get();
        return view('dashboard.products.update', compact('product', 'categories', 'variants'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id'],
            'variant_id' => ['required', 'exists:variants,id'],
            'image' => ['nullable', 'image', 'max:1024'],
            'code' => ['unique:products,code,' . $id], // Pastikan kode tetap unik, kecuali untuk produk ini sendiri
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $imagePath = $request->file('image')->store('products');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'variant_id' => $request->variant_id,
            'updated_by' => Auth::id(),
        ]);

        return redirect('/barang')->with('message', 'Data Successfully Updated');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::delete($product->image);
        }

        if ($product->delete()) {
            return response()->json(['message' => 'Data Successfully Deleted'], 200);
        } else {
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }

    public function getAllProducts()
    {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    public function exportExcel()
    {
        return Excel::download(new ProductExport, 'product.xlsx');
    }

    public function generateNoproduct(Request $request)
    {
        $category = Category::find($request->category_id);
        $variant = Variant::find($request->variant_id);

        if (!$category || !$variant) {
            return response()->json(['error' => 'Category or Variant not found'], 400);
        }

        $lastProduct = Product::where('category_id', $request->category_id)
                              ->where('variant_id', $request->variant_id)
                              ->orderBy('code', 'desc')
                              ->first();

        if ($lastProduct) {
            preg_match('/(\d{4})$/', $lastProduct->code, $matches);
            $lastCode = isset($matches[1]) ? (int) $matches[1] : 0;
            $nextNumber = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $uniqueCode = strtoupper($category->code . '-' . $variant->code . '-' . $nextNumber);

        return response()->json(['unique_code' => $uniqueCode]); // ✅ Kembalikan JSON
    }


    public function getVariants(Request $request)
    {
        $variants = Variant::where('category_id', $request->category_id)->get();
        return response()->json(['variants' => $variants]);
    }
}
