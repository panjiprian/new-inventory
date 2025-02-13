@extends('layouts.main')

@section('container')
<div class="justify-items">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Input Variant</h2>
        </div>

        <form id="variantForm" action="/input-varian" method="POST" autocomplete="off">
            @csrf
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="category_id">Category</label>
                <div class="border-2 p-1 @error('category_id') border-red-400 @enderror">
                    <select name="category_id" id="category_id" class="w-full h-full focus:outline-none text-sm">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{$category->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Variant Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input name="name" value="{{ old('name') }}" class="w-full h-full focus:outline-none text-sm" id="name" type="text" autofocus>
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="code">Variant Code</label>
                <div class="border-2 p-1 @error('code') border-red-400 @enderror">
                    <input name="code" value="{{ old('code') }}" class="w-full h-full focus:outline-none text-sm" id="code" type="text">
                </div>
                @error('code')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <button type="button" id="btnSubmit" class="btn btn-save bg-blue-500 text-white w-full mt-2 p-2 rounded text-sm flex items-center justify-center"
                        style="background-color: #3085d6;">
                    Save Variant
                </button>
                <a class="bg-red-500 text-white w-full mt-2 p-2 rounded text-sm flex items-center justify-center"
                        href="/varian" style="background-color: #d33;">Back</a>
            </div>
        </form>
    </div>
    <script>
        document.getElementById("btnSubmit").addEventListener("click", function(event) {
            event.preventDefault(); // Mencegah submit langsung

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to save this variant?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, save it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("variantForm").submit();
                }
            });
        });
        </script>
</div>
@endsection
