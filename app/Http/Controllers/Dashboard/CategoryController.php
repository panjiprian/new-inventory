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
            ->select(
                'categories.*',
                'creator.name as creator_name',
                'updater.name as updater_name'
            );

        if ($request->has('search')) {
            $query->where('categories.name', 'LIKE', "%{$request->search}%")
                  ->orWhere('categories.code', 'LIKE', "%{$request->search}%");
        }

        $perPage = $request->input('per_page', 10); // Default 10 data per halaman
        $categories = $query->paginate($perPage);

        return view('dashboard.category.index', compact('categories'));
    }




    public function create()
    {
        return view('dashboard.category.input');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => ['required', 'unique:categories,code', 'max:10'],
            'name' => ['required', 'unique:categories,name']
        ]);

        $created = Category::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'created_by' => Auth::user()->id,
        ]);

        if ($created) {
            return redirect('/kategori')->with('message', 'Category Successfully Added');
        }
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $deleted = $category->delete();

        if ($deleted) {
            session()->flash('message', 'Category Successfully Deleted');
            return response()->json(['message' => 'Category Successfully Deleted'], 200);
        }
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('dashboard.category.update', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => ['required', 'unique:categories,code,' . $id, 'max:10'],
            'name' => ['required', 'unique:categories,name,' . $id]
        ]);

        $category = Category::findOrFail($id);
        $updated = $category->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
        ]);

        if ($updated) {
            return redirect('/kategori')->with('message', 'Category Successfully Updated');
        }
    }

    public function exportExcel()
    {
        return Excel::download(new CategoryExport, 'categories.xlsx');
    }
}
