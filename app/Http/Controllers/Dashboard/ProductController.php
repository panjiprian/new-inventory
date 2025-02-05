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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Process;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = DB::table('products')
                // Join untuk created_by
                ->join('users as created_user', 'products.created_by', '=', 'created_user.id')
                // Join untuk updated_by
                ->leftJoin('users as updated_user', 'products.updated_by', '=', 'updated_user.id')
                // Join ke tabel categories
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                // Select kolom yang diinginkan
                ->select(
                    'products.*',
                    'created_user.name as created_user_name',
                    'updated_user.name as updated_user_name',
                    'categories.name as category_name'
                );


        // Fungsi Pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('products.name', 'LIKE', "%{$search}%");
        }

        $products = $query->paginate(10);

        return view('dashboard.products.index', ['products' => $products]);
    }




    public function delete ($id) {
        $product = Product::findOrFail($id);
        Storage::delete($product->image);
        $deletedProduct = $product->delete();

        if($deletedProduct){
            session()->flash('message', 'Data Successfully Delete');
            return response()->json(['message'=> 'Data Successfully Delete'],200);
        }
    }

    public function create () {
        $category = Category::all();
        $supplier = Supplier::all();
        return view('dashboard.products.input', ['categories'=> $category, 'suppliers'=>$supplier]);
    }

    public function store (Request $request) {
        $validated = $this->validate($request,[
            'name'=>['required'],
            'price'=>['required'],
            'image'=>['required', 'image','max:1024'],
            'category_id'=>['required'],

        ]);

        $imagePath = $request->file('image')->store('products');

        $created = Product::create([
            'name'=>$request->name,
            'price'=>$request->price,
            'image'=>$imagePath,
            'category_id'=>$request->category_id,
            'created_by'=>Auth::user()->id
        ]);
        if($created){
            return redirect('/barang')->with('message', 'Data Successfully Added');
        }
    }

    public function edit ($id) {
        $product = Product::findOrFail($id);
        $category = Category::all();
        $supplier = Supplier::all();
        return view('dashboard.products.update', ["product"=>$product, 'categories'=>$category,'suppliers'=>$supplier]);
    }

    public function update (Request $request, $id) {





        $validated = $this->validate($request,[
            'name'=>['required'],
            'price'=>['required'],
            'category_id'=>['required'],
            'image'=>['required', 'image', 'max:1024'],
        ]);

        $productWithId = Product::findOrFail($id);
        Storage::delete($productWithId->image);
        $imagePath = $request->file('image')->store('products');
        $updated = $productWithId->update([
            'name'=>$request->name,
            'price'=>$request->price,
            'image'=>$imagePath,
            'category_id'=>$request->category_id,
            'updated_by'=>Auth::user()->id
        ]);

        if($updated){
            return redirect('/barang')->with('message', 'Data Successfully Updated');
        }

    }

    public function getAllProducts () {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }

    public function exportExcel () {
        return Excel::download(new ProductExport, 'product.xlsx');
    }
}
