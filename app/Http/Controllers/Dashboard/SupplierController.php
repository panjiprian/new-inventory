<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SupplierExport;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index (Request $request) {
        if($request->has('search')){
            $suppliers = Supplier::where('name', 'LIKE' ,"%{$request->search}%")->paginate(10);
        } else {
            $suppliers = Supplier::paginate(10);
        }
        return view('dashboard.supplier.index', ['suppliers'=> $suppliers]);
    }

    public function delete ($id) {
        $deleted = Supplier::findOrFail($id)->delete();

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'Supplier Successfully Deleted' : 'Failed to delete supplier'
        ], $deleted ? 200 : 500);
    }

    public function create () {
        return view('dashboard.supplier.input');
    }

    public function store (Request $request) {
        $validated = $request->validate([
            'name'=> ['required'],
            'address'=>['required'],
            'email'=>['required'],
            'phone'=>['required'],
        ]);

        try {
            $created = Supplier::create($validated);
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

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('dashboard.supplier.update',['supplier'=>$supplier]);
    }

    public function update(Request $request, $id) {
        $supplier = Supplier::findOrFail($id);
        $validated = $request->validate([
            'name'=> ['required'],
            'address'=>['required'],
            'email'=>['required'],
            'phone'=>['required'],
        ]);

        try {
            $updated = $supplier->update($validated);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data Successfully Updated!',
                'data' => $updated,
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

    public function getAllSuppliers () {
        $supplier = Supplier::all();
        return response()->json(['data' => $supplier], 200);
    }

    public function exportExcel () {
        return Excel::download(new SupplierExport, 'suppliers.xlsx');
    }
}
