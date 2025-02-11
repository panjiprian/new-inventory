<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Category;
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
        $this->validate($request, [
            'name' => ['required', 'unique:variants,name'],
            'code' => ['required', 'unique:variants,code'],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $created = Variant::create([
            'code' => $request->code,
            'name' => $request->name,
            'category_id' => $request->category_id,
            'created_by' => Auth::user()->id,
        ]);
        if ($created) {
            return redirect('/varian')->with('success', 'Variant Successfully Added');
        } else {
            return back()->with('error', 'Failed to add variant');
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

        $request->validate([
            'name' => ['required', Rule::unique('variants', 'name')->ignore($id)],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        // Pastikan code tidak berubah
        $updated = $variant->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'updated_by' => Auth::id(),
        ]);

        if ($updated) {
            return redirect('/varian')->with('success', 'Variant Successfully Updated');
        } else {
            return back()->with('error', 'Failed to update variant');
        }
    }

    public function exportExcel()
    {
        return Excel::download(new VariantExport, 'variants.xlsx');
    }
}
