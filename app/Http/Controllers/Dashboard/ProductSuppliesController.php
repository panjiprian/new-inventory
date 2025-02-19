<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSupplies;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductSuppliesController extends Controller
{


    public function indexIncome()
    {
        $productsIncome = ProductSupplies::with(['product', 'user', 'supplier'])->where('type', '=', 'income')->paginate(10);
        return view('dashboard.income.index', ['productsIncome' => $productsIncome]);
    }

    public function indexOutcome()
    {
        $productsOutcome = ProductSupplies::with(['product', 'user', 'supplier'])->where('type', 'outcome')->paginate(10);
        return view('dashboard.outcome.index', ['productsOutcome' => $productsOutcome]);
    }

    public function createIncome()
    {
        $suppliers = Supplier::all(); // Ambil semua data supplier
        $products = Product::all();   // Ambil semua data produk jika diperlukan
        return view('dashboard.income.input', compact('suppliers', 'products'));
    }

    public function createOutcome()
    {
        $suppliers = Supplier::all(); // Ambil semua data supplier
        $products = Product::all();   // Ambil semua data produk jika diperlukan
        return view('dashboard.outcome.input', compact('suppliers', 'products'));
    }

    public function storeIncome(Request $request)
    {
        $validatedData = $request->validate([
            'date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ]);

        try {
            DB::beginTransaction();

            $created = ProductSupplies::create([
                'product_id' => $validatedData['product_id'],
                'supplier_id' => $validatedData['supplier_id'],
                'user_id' => Auth::user()->id,
                'date' => $validatedData['date'],
                'quantity' => $validatedData['quantity'],
                'type' => 'income'
            ]);

            $sumIncomeQuantity = ProductSupplies::where('type', 'income')->where('product_id', $validatedData['product_id'])->sum('quantity');
            $sumOutcomeQuantity = ProductSupplies::where('type', 'outcome')->where('product_id', $validatedData['product_id'])->sum('quantity');

            $product = Product::findOrFail($validatedData['product_id']);
            $product->update(['stock' => ($sumIncomeQuantity - $sumOutcomeQuantity)]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Successfully Added!',
                'data' => $created,
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

    public function storeOutcome(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'date' => ['required', 'date'],
        'quantity' => ['required', 'integer', 'min:1'],
        'product_id' => ['required', 'exists:products,id'],
        'supplier_id' => ['required', 'exists:suppliers,id'],
    ]);

    // Cek stok produk
    $product = Product::findOrFail($validatedData['product_id']);
    $currentStock = $product->stock;

    if ($validatedData['quantity'] > $currentStock) {
        // Kembalikan response JSON untuk error stok
        return response()->json([
            'success' => false,
            'message' => 'Quantity exceeds available stock.',
        ], 400); // Status 400 menandakan error validasi
    }

    // Lanjutkan proses transaksi
    try {
        DB::beginTransaction();

        // Buat data ProductSupplies dengan tipe 'outcome'
        ProductSupplies::create([
            'product_id' => $validatedData['product_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'user_id' => Auth::user()->id,
            'date' => $validatedData['date'],
            'quantity' => $validatedData['quantity'],
            'type' => 'outcome',
        ]);

        // Update stok produk
        $product->update(['stock' => $currentStock - $validatedData['quantity']]);

        DB::commit();

        // Kembalikan response JSON sukses
        return response()->json([
            'success' => true,
            'message' => 'Data Successfully Added!',
        ]);
    } catch (\Exception $e) {
        DB::rollBack();

        // Kembalikan response JSON jika terjadi error
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500); // Status 500 untuk error server
    }
}

    public function deleteProductSupply($id)
    {
        try {
            DB::beginTransaction();

            $productSupply = ProductSupplies::findOrFail($id);
            $product = Product::findOrFail($productSupply->product_id);

            $productSupply->delete();

            $sumIncomeQuantity = ProductSupplies::where('type', 'income')->where('product_id', $productSupply->product_id)->sum('quantity');
            $sumOutcomeQuantity = ProductSupplies::where('type', 'outcome')->where('product_id', $productSupply->product_id)->sum('quantity');
            $product->update(['stock' => ($sumIncomeQuantity - $sumOutcomeQuantity)]);

            DB::commit();

            session()->flash('message', 'Data Successfully Deleted');
            return response()->json(['message' => 'Data Successfully Deleted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function editIncome($id)
    {
        $productIncome = ProductSupplies::findOrFail($id);
        $products = Product::all(); // Ambil semua produk untuk dropdown
        $suppliers = Supplier::all(); // Ambil semua supplier untuk dropdown

        return view('dashboard.income.update', [
            'productIncome' => $productIncome,
            'products' => $products,
            'suppliers' => $suppliers
        ]);
    }

    public function editOutcome($id)
    {
        $productOutcome = ProductSupplies::findOrFail($id);
        $products = Product::all(); // Ambil semua produk untuk dropdown
        $suppliers = Supplier::all(); // Ambil semua supplier untuk dropdown

        return view('dashboard.outcome.update', [
            'productOutcome' => $productOutcome,
            'products' => $products,
            'suppliers' => $suppliers
        ]);
    }


    public function updateIncome(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ]);

        try {
            DB::beginTransaction();

            $productIncome = ProductSupplies::findOrFail($id);
            $product = Product::findOrFail($productIncome->product_id);

            $productIncome->update($validatedData);

            $sumIncomeQuantity = ProductSupplies::where('type', 'income')->where('product_id', $validatedData['product_id'])->sum('quantity');
            $sumOutcomeQuantity = ProductSupplies::where('type', 'outcome')->where('product_id', $validatedData['product_id'])->sum('quantity');
            $product->update(['stock' => ($sumIncomeQuantity - $sumOutcomeQuantity)]);

            DB::commit();

            return redirect('/barang-masuk')->with('message', 'Data Successfully Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/barang-masuk')->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
        }
    }

    public function updateOutcome(Request $request, $id)
    {
        $productOutcome = ProductSupplies::findOrFail($id);
        $product = Product::findOrFail($productOutcome->product_id);

        // Validasi input
        $validatedData = $request->validate([
            'date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'exists:products,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
        ]);

        $currentStock = $product->stock;
        $previousQuantity = $productOutcome->quantity;

        // Hitung stok baru berdasarkan perbedaan kuantitas
        $newStock = $currentStock + $previousQuantity - $validatedData['quantity'];

        // Cek jika stok baru tidak mencukupi
        if ($newStock < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity exceeds available stock.',
            ], 400); // Status 400 untuk error validasi
        }

        try {
            DB::beginTransaction();

            // Update data ProductSupplies
            $productOutcome->update($validatedData);

            // Update stok produk
            $product->update(['stock' => $newStock]);

            DB::commit();

            // Kembalikan response JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Data Successfully Updated!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Kembalikan response JSON jika terjadi error
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500); // Status 500 untuk error server
        }
    }

}
