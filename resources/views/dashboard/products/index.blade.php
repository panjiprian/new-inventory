@extends('layouts.main')

@section('container')

@if (session('message'))
   <div id="toast-container" class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
    <div class=" text-green-400 text-sm font-bold capitalize">{{session()->get('message')}}</div>
</div>
@endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg">
            <div class="flex justify-between">
                <div class="text-left">
                    <h2 class="text-gray-600 font-bold">Product Data</h2>
                    @if(Auth::user()->role === 'admin')
                    <a href="/input-barang" class="text-sm inline-block bg-gray-700 text-white mt-2 px-2 py-1">Input Product</a>
                    @endif
                    <a  class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1" href="/excel/products">Export Excel</a>
                </div>
                <form method="get" action="/barang" class="form">
                    <div class="border p-1 px-2 rounded flex items-center gap-2">
                        <input id="from_date" name="from_date" type="date" class="focus:outline-none text-sm w-20">
                        <input id="to_date" name="to_date" type="date" class="focus:outline-none text-sm w-20">
                        <input id="search" name="search" class="focus:outline-none text-sm w-40" type="text" placeholder="Search Product">
                        <button type="submit" class="text-sm bg-gray-700 p-2 rounded text-white">Search</button>
                    </div>
                </form>
            </div>

            <table class="w-full mt-5 text-sm text-gray-600">
                <thead>
                    <tr class="font-bold border-b-2 p-2">
                        <td class="p-2">No</td>
                        <td class="p-2">Product Code</td>
                        <td class="p-2">Product Name</td>
                        <td class="p-2">Description</td>
                        <td class="p-2">Price</td>
                        <td class="p-2">Current Stock</td>
                        <td class="p-2">Category</td>
                        <td class="p-2">Variant</td>
                        <td class="p-2">Photo</td>
                        <td class="p-2">Create</td>
                        <td class="p-2">Update</td>
                        <td class="p-2">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b p-2">
                        <td class="p-2">{{$loop->iteration}}</td>
                        <td class="p-2">{{$product->code}}</td>
                        <td class="p-2">{{$product->name}}</td>
                        <td class="p-2">{{$product->description}}</td>
                        <td class="p-2">Rp.{{number_format($product->price,0) }}</td>
                        <td class="p-2">{{$product->stock}}</td>
                        <td class="p-2">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-2">{{ $product->variant->name ?? '-' }}</td>
                        <td class="p-2 w-[150px]">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/default-product.png') }}" class="w-full h-auto rounded">
                        </td>

                        <td class="p-2">
                            @if ($product->created_user_name)
                                {{ $product->created_user_name }} ({{ \Carbon\Carbon::parse($product->created_at)->format('d M Y') }})
                            @else
                                -
                            @endif
                        </td>
                        <td class="p-2">
                            @if ($product->updated_user_name)
                                {{ $product->updated_user_name }} ({{ \Carbon\Carbon::parse($product->updated_at)->format('d M Y') }})
                            @else
                                -
                            @endif
                        </td>
                        @if(Auth::user()->role === 'admin')
                        <td class="p-2 flex gap-2">
                            <button data-id="{{$product->id}}" class="btn-delete-product bg-red-500 py-1 px-4 rounded text-white">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <a href="/ubah-barang/{{$product->id}}" class="bg-yellow-400 py-1 px-4 rounded text-white">
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-5">
                {{$products->links('pagination::tailwind')}}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let toast = document.getElementById("toast-container");
            if (toast) {
                toast.classList.remove("hidden");
                setTimeout(() => {
                    toast.classList.add("hidden");
                }, 3000);
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-delete-product").forEach(button => {
                button.addEventListener("click", function () {
                    let productId = this.getAttribute("data-id");
                    if (confirm("Are you sure you want to delete this product?")) {
                        fetch(`/delete-product/${productId}`, {
                            method: "DELETE",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                alert("Product deleted successfully!");
                                location.reload();
                            } else {
                                alert("Failed to delete product!");
                            }
                        })
                        .catch(error => console.error(error));
                    }
                });
            });
        });
    </script>

@endsection
