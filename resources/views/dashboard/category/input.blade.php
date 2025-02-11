@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Input Category</h2>
        </div>

        <form id="categoryForm" action="/input-kategori" method="POST" class="w-1/2 mt-5">
            @csrf
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Category Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input type="text" name="name" id="name" class="w-full p-2 border rounded-lg" value="{{ old('name', $category->name ?? '') }}" required>
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="code">Category Code</label>
                <div class="border-2 p-1 @error('code') border-red-400 @enderror">
                    <input type="text" name="code" id="code" class="w-full p-2 border rounded-lg" value="{{ old('code', $category->code ?? '') }}" required>
                </div>
                @error('code')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <button type="submit" class="btn-save bg-gray-600 text-white w-full p-2 rounded text-sm" id="btnSave">Save Category</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/index.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("categoryForm");
        const submitBtn = document.getElementById("btnSave");

        form.addEventListener("submit", function (event) {
            event.preventDefault(); // ⛔ Hindari submit langsung, hanya pakai fetch di index.js
            if (submitBtn.disabled) return;
            submitBtn.disabled = true; // 🚀 Hindari klik ganda

            setTimeout(() => { submitBtn.disabled = false; }, 3000); // ✅ Aktifkan lagi setelah 3 detik (fallback)
        });
    });
</script>
@endsection
