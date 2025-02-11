<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VariantExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $perPage = $request->input('per_page', 10);
        $variants = $query->paginate($perPage);

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
            'code' => $request->code, // Biarkan user input manual
            'name' => $request->name,
            'category_id' => $request->category_id,
            'created_by' => Auth::user()->id,
        ]);

        if ($created) {
            return response()->json([
                'success' => true,
                'message' => 'Variant Successfully Added',
                'redirect' => url('/varian') // Redirect URL setelah sukses
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add variant'
            ], 500);
        }
    }


    public function delete($id)
    {
        $variant = Variant::findOrFail($id);
        $deleted = $variant->delete();

        if ($deleted) {
            session()->flash('message', 'Variant Successfully Deleted');
            return response()->json(['message' => 'Variant Successfully Deleted'], 200);
        }
    }

    public function edit($id)
    {
        $variant = Variant::findOrFail($id);
        $categories = Category::all();
        return view('dashboard.variant.update', compact('variant', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:variants,name,' . $id],
            'code' => ['required', 'unique:variants,code,' . $id],
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $variant = Variant::findOrFail($id);
        $updated = $variant->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'category_id' => $request->category_id,
            'updated_by' => Auth::user()->id,
        ]);

        if ($updated) {
            return redirect('/varian')->with([
                'message' => 'Data Successfully Updated',
                'alert-type' => 'success'
            ]);
        } else {
            return redirect()->back()->with([
                'message' => 'Failed to update variant',
                'alert-type' => 'error'
            ]);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new VariantExport, 'variants.xlsx');
    }
}
