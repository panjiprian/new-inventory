<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoryExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query()
            ->leftJoin('users as creator', 'categories.created_by', '=', 'creator.id')
            ->leftJoin('users as updater', 'categories.updated_by', '=', 'updater.id')
            ->select('categories.*', 'creator.name as creator_name', 'updater.name as updater_name');

        if ($request->has('search')) {
            $query->where('categories.name', 'LIKE', "%{$request->search}%")
                  ->orWhere('categories.code', 'LIKE', "%{$request->search}%");
        }

        $categories = $query->paginate($request->input('per_page', 10));

        return view('dashboard.category.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.category.input');
    }

    public function store(Request $request)
    {
        // Konversi kode ke huruf besar SEBELUM validasi
        $request->merge(['code' => strtoupper($request->code)]);

        $validated = $request->validate([
            'code' => ['required', 'unique:categories,code', 'max:10'],
            'name' => ['required', 'unique:categories,name']
        ]);

        $validated['created_by'] = Auth::user()->id;

        $created = Category::create($validated);

        if ($created) {
            return redirect('/kategori')->with('success', 'Category Successfully Added');
        } else {
            return back()->with('error', 'Failed to add category');
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('dashboard.category.update', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Konversi kode ke huruf besar SEBELUM validasi
        $request->merge(['code' => strtoupper($request->code)]);

        $validated = $request->validate([
            'code' => ['required', 'unique:categories,code,' . $id, 'max:10'],
            'name' => ['required', 'unique:categories,name,' . $id]
        ]);

        $validated['updated_by'] = Auth::user()->id;

        $updated = $category->update($validated);

        if ($updated) {
            return redirect('/kategori')->with('success', 'Category Successfully Updated');
        } else {
            return back()->with('error', 'Failed to update category');
        }
    }


    public function delete($id)
    {
        $deleted = Category::findOrFail($id)->delete();

        return response()->json([
            'success' => (bool) $deleted,
            'message' => $deleted ? 'Category Successfully Deleted' : 'Failed to delete category'
        ], $deleted ? 200 : 500);
    }

    public function exportExcel()
    {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }
}
