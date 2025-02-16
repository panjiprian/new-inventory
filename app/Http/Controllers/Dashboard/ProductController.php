<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Traits\WhatsappTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;


class ProductController extends Controller
{
    use WhatsappTrait;
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
        // Validasi request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'max:1024'],
            'category_id' => ['required', 'exists:categories,id'],
            'variant_id' => ['required', 'exists:variants,id'],
            'unique_code' => ['required', 'unique:products,code'],
        ]);

        // Menyimpan file gambar jika ada
        $imagePath = $request->file('image') ? $request->file('image')->store('products') : null;

        // Ambil semua admin
        $admins = User::where('role', 'officer')->whereNotNull('phone')->get();

        // Pesan yang ingin dikirim
        $message = 'Produk baru telah ditambahkan: ' . $request->name . ' dengan harga Rp ' . number_format($request->price, 0, ',', '.');

        // Mulai transaksi untuk memastikan konsistensi data
        DB::beginTransaction();

        try {
            // Kirim pesan ke setiap admin
            // foreach ($admins as $admin) {
            //     $phone = str_replace('+', '', $admin->phone);
            //     $isSent = $this->kirimPesanWhatsapp($phone, $message);

            //     // Jika pengiriman pesan ke salah satu admin gagal, batalkan proses
            //     if (!$isSent) {
            //         throw new \Exception("Pesan WhatsApp gagal dikirim ke admin: " . $admin->name);
            //     }
            // }

            // Jika semua pesan berhasil, simpan produk
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'category_id' => $request->category_id,
                'variant_id' => $request->variant_id,
                'code' => $request->unique_code,
                'created_by' => Auth::id(),
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Successfully Added!',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
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
            'image' => ['nullable', 'image', 'max:1024'],
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $imagePath = $request->file('image')->store('products');
        }

        try {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'updated_by' => Auth::id(),
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data Successfully Updated!',
                'data' => $product,
            ], 201);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
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
        $categoryId = $request->category_id;
        $variantId = $request->variant_id;
        $category = Category::find($categoryId);
        $variant = Variant::find($variantId);
        if (!$category || !$variant) {
            return response()->json(['error' => 'Category or Variant not found'], 400);
        }
        $lastProduct = Product::where('category_id', $categoryId)
            ->where('variant_id', $variantId)
            ->orderBy('id', 'desc')
            ->first();
        // dd( $lastProduct);
        if ($lastProduct) {
            $lastCode = (int) ltrim(substr($lastProduct->code, -4), '0'); // Ambil angka terakhir tanpa menghapus nol
            $nextNumber = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT); // Tambahkan angka dan tetap 4 digit
        } else {
            $nextNumber = '0001';
        }
        $uniqueCode = strtoupper($category->code . '-' . $variant->code . '-' . $nextNumber);
        return response()->json(['unique_code' => $uniqueCode]);
    }


    public function getVariants(Request $request)
    {
        $variants = Variant::where('category_id', $request->category_id)->get();
        return response()->json(['variants' => $variants]);
    }
}
