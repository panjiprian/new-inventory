@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Update Product</h2>
        </div>

        <form action="/ubah-barang/{{$product->id}}" method="POST" enctype="multipart/form-data" class="w-1/2 mt-5">
            @csrf
            @method('PUT')
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="unique_code">Unique Code</label>
                <div class="border-2 p-1 bg-gray-200 @error('unique_code') border-red-400 @enderror">
                    <input name="unique_code" value="{{$product->unique_code}}" class="text-black w-full h-full focus:outline-none text-sm bg-gray-200" id="unique_code" type="text" readonly>
                </div>
                @error('unique_code')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Product Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input name="name" value="{{$product->name}}" class="text-black w-full h-full focus:outline-none text-sm" id="name" type="text">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="price">Price</label>
                <div class="@error('price') border-red-400 @enderror border-2 p-1">
                    <input value="{{$product->price}}" name="price" class="text-black text-sm w-full h-full focus:outline-none" id="price" type="number">
                </div>
                @error('price')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="image">Photo</label>
                <div class="@error('image') border-red-400 @enderror border-2 p-1">
                    <input type="file" name="image" class="text-sm w-full h-full focus:outline-none" id="image">
                </div>
                @error('image')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="flex gap-1 mt-3">
                <div class="w-full">
                    <label class="text-sm text-gray-600" for="category">Category</label>
                    <div class="border">
                        <select name="category_id" class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="category">
                            @foreach($categories as $category)
                                <option class="text-sm" value="{{$category->id}}" {{$product->category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex gap-1 mt-3">
                <div class="w-full">
                    <label class="text-sm text-gray-600" for="variant">Variant</label>
                    <div class="border">
                        <select name="variant_id" class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="variant">
                            @foreach($variants as $variant)
                                @if($variant->category_id == $product->category_id)
                                    <option class="text-sm" value="{{$variant->id}}" {{$product->variant_id == $variant->id ? 'selected' : ''}}>{{$variant->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="description">Description</label>
                <div class="border-2 p-1 @error('description') border-red-400 @enderror">
                    <textarea name="description" class="text-black w-full h-full focus:outline-none text-sm" id="description">{{$product->description}}</textarea>
                </div>
                @error('description')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <button class="bg-gray-600 text-white w-full p-2 rounded text-sm">Update Product</button>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('category').addEventListener('change', function() {
        let categoryId = this.value;
        let variantSelect = document.getElementById('variant');

        fetch(`/get-variants?category_id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                variantSelect.innerHTML = "";
                data.variants.forEach(variant => {
                    let option = document.createElement("option");
                    option.value = variant.id;
                    option.textContent = variant.name;
                    variantSelect.appendChild(option);
                });
            });
    });
</script>
@endsection
