@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Update Category</h2>
        </div>

        <form id="updateCategoryForm" action="/ubah-kategori/{{$category->id}}" method="POST" class="w-1/2 mt-5">
            @csrf
            @method('PUT')

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Category Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input name="name" value="{{$category->name}}" class="w-full h-full focus:outline-none text-sm" id="name" type="text">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="code">Category Code</label>
                <div class="border-2 p-1 @error('code') border-red-400 @enderror">
                    <input name="code" value="{{$category->code}}" class="w-full h-full focus:outline-none text-sm" id="code" type="text">
                </div>
                @error('code')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <button type="button" id="btnUpdate" class="btn btn-update bg-gray-600 text-white w-full p-2 rounded text-sm">
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById("btnUpdate").addEventListener("click", function(event) {
    event.preventDefault(); // Mencegah submit langsung

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update this category?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("updateCategoryForm").submit();
        }
    });
});
</script>
@endsection
