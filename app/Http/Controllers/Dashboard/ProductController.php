<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->join('users as created_user', 'products.created_by', '=', 'created_user.id')
            ->leftJoin('users as updated_user', 'products.updated_by', '=', 'updated_user.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('variants', 'products.variant_id', '=', 'variants.id')
            ->select(
                'products.*',
                'created_user.name as created_user_name',
                'updated_user.name as updated_user_name',
                'categories.name as category_name',
                'variants.name as variant_name'
            );

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('products.name', 'LIKE', "%{$search}%");
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $from = Carbon::parse($request->input('from_date'))->startOfDay();
            $to = Carbon::parse($request->input('to_date'))->endOfDay();
            $query->whereBetween('products.created_at', [$from, $to]);
        }

        $perPage = $request->input('per_page', 10); // Default 10 data per halaman
        $products = $query->paginate($perPage);

        return view('dashboard.products.index', ['products' => $products]);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        Storage::delete($product->image);
        $deletedProduct = $product->delete();

        if ($deletedProduct) {
            session()->flash('message', 'Data Successfully Deleted');
            return response()->json(['message' => 'Data Successfully Deleted'], 200);
        }
    }

    public function create()
    {
        $categories = Category::all();
        $variants = Variant::all();
        return view('dashboard.products.input', ['categories' => $categories, 'variants' => $variants]);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request,[
            'name' => ['required'],
            'price' => ['required'],
            'image' => ['required', 'image', 'max:1024'],
            'category_id' => ['required'],
            'variant_id' => ['required'],
        ]);

        $imagePath = $request->file('image')->store('products');

        $uniqueId = str_pad(Product::max('id') + 1, 4, '0', STR_PAD_LEFT);
        $productCode = strtoupper($request->category_id . '-' . $request->variant_id . '-' . $uniqueId);

        $created = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'variant_id' => $request->variant_id,
            'product_code' => $productCode,
            'created_by' => Auth::user()->id,
        ]);

        if ($created) {
            return redirect('/barang')->with('message', 'Data Successfully Added');
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $variants = Variant::all();
        return view('dashboard.products.update', ['product' => $product, 'categories' => $categories, 'variants' => $variants]);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validate($request,[
            'name' => ['required'],
            'price' => ['required'],
            'category_id' => ['required'],
            'variant_id' => ['required'],
            'image' => ['image', 'max:1024'],
        ]);

        $productWithId = Product::findOrFail($id);
        $imagePath = $productWithId->image;

        if ($request->hasFile('image')) {
            Storage::delete($productWithId->image);
            $imagePath = $request->file('image')->store('products');
        }

        $updated = $productWithId->update([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'variant_id' => $request->variant_id,
            'updated_by' => Auth::user()->id,
        ]);

        if ($updated) {
            return redirect('/barang')->with('message', 'Data Successfully Updated');
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
}
