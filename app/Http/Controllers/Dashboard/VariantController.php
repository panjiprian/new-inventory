<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VariantExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VariantController extends Controller
{
    public function index(Request $request)
    {
        $query = Variant::query()
            ->leftJoin('categories', 'variants.category_id', '=', 'categories.id')
            ->leftJoin('users as creators', 'variants.created_by', '=', 'creators.id')
            ->leftJoin('users as updaters', 'variants.updated_by', '=', 'updaters.id')
            ->select(
                'variants.*',
                'categories.name as category_name',
                'creators.name as creator_name',
                'updaters.name as updater_name'
            );

        if ($request->has('search')) {
            $query->where('variants.name', 'LIKE', "%{$request->search}%")
                  ->orWhere('variants.code', 'LIKE', "%{$request->search}%");
        }

        $variants = $query->paginate($request->input('per_page', 10));

        return view('dashboard.variant.index', compact('variants'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('dashboard.variant.input', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'unique:variants,name'],
            'code' => ['required', 'unique:variants,code'],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $validated['created_by'] = Auth::user()->id;


        try {
            $created = Variant::create($validated);
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


    public function delete($id)
    {
        $variant = Variant::findOrFail($id);
        $deleted = $variant->delete();

        return response()->json([
            'message' => $deleted ? 'Variant Successfully Deleted' : 'Failed to delete variant'
        ], $deleted ? 200 : 500);
    }

    public function edit($id)
    {
        $variant = Variant::findOrFail($id);
        $categories = Category::all();
        return view('dashboard.variant.update', compact('variant', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $variant = Variant::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', Rule::unique('variants', 'name')->ignore($id)],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $validated['updated_by'] = Auth::user()->id;

        try {
            $updated = $variant->update($validated);
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

    public function exportExcel()
    {
        return Excel::download(new VariantExport, 'variants.xlsx');
    }
}
