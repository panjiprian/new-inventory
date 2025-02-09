@extends('layouts.main')

@section('container')
<div class="justify-items">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Input Variant</h2>
        </div>

        <form action="/input-varian" method="POST" class="w-1/2 mt-5">
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
                    <input name="name" value="{{ old('name') }}" class="w-full h-full focus:outline-none text-sm" id="name" type="text">
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
                <button class="bg-gray-600 text-white w-full p-2 rounded text-sm">Save Variant</button>
            </div>
        </form>
    </div>
</div>
@endsection
