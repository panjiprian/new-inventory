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
        $query = Variant::with(['category', 'creator', 'updater', 'products']);

        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('code', 'LIKE', "%{$request->search}%");
        }

        $variants = $query->paginate(10);
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
            'code' => strtoupper($request->code), // Simpan code dalam huruf besar
            'name' => $request->name,
            'category_id' => $request->category_id,
            'created_by' => Auth::user()->id,
        ]);

        if ($created) {
            return redirect('/varian')->with('message', 'Data Successfully Added');
        }
    }



    public function delete($id)
    {
        $variant = Variant::findOrFail($id);
        $deleted = $variant->delete();

        if ($deleted) {
            session()->flash('message', 'Data Successfully Deleted');
            return response()->json(['message' => 'Data Successfully Deleted'], 200);
        }
    }

    public function edit($id)
    {
        $variant = Variant::findOrFail($id);
        $categories = Category::all(); // Kirim kategori ke view
        return view('dashboard.variant.update', compact('variant', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'unique:variants,name,' . $id],
            'code'=> ['required', 'unique:variants,code,' . $id],
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
            return redirect('/varian')->with('message', 'Data Successfully Updated');
        }
    }

    public function exportExcel()
    {
        return Excel::download(new VariantExport, 'variants.xlsx');
    }
}
