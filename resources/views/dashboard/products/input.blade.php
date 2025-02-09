@extends('layouts.main')

@section('container')
    <div class="container px-4">
        <div class="bg-white p-5 mt-5 rounded-lg">
            <div class="flex">
                <h2 class="text-gray-600 font-bold">Input New Product</h2>
            </div>

            <form action="/input-barang" method="POST" enctype="multipart/form-data" class="w-1/2 mt-5">
                @csrf

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="unique_code">Unique Code</label>
                    <div class="border-2 p-1 @error('unique_code') border-red-400 @enderror">
                        <input name="unique_code" id="unique_code" value=""
                            class="text-black w-full h-full focus:outline-none text-sm" type="text" disabled>
                    </div>
                    @error('unique_code')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="name">Product Name</label>
                    <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                        <input name="name" value="{{ old('name') }}"
                            class="text-black w-full h-full focus:outline-none text-sm" id="name" type="text">
                    </div>
                    @error('name')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="price">Price</label>
                    <div class="border-2 p-1 @error('price') border-red-400 @enderror">
                        <input value="{{ old('price') }}" name="price"
                            class="text-black text-sm w-full h-full focus:outline-none" id="price" type="number">
                    </div>
                    @error('price')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="image">Photo</label>
                    <div class="border-2 p-1 @error('image') border-red-400 @enderror">
                        <input type="file" name="image" class="text-sm w-full h-full focus:outline-none"
                            id="image">
                    </div>
                    @error('image')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-1 mt-3">
                    <div class="w-full">
                        <label class="text-sm text-gray-600" for="category">Category</label>
                        <div class="border">
                            <select name="category_id"
                                class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="category">
                                <option value="" selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option class="text-sm" value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-1 mt-3">
                    <div class="w-full">
                        <label class="text-sm text-gray-600" for="variant">Variant</label>
                        <div class="border">
                            <select name="variant_id"
                                class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="variant">
                                <option value="" selected disabled>Select Variant</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="description">Description</label>
                    <div class="border-2 p-1 @error('description') border-red-400 @enderror">
                        <textarea name="description" id="description" class="text-black w-full h-full focus:outline-none text-sm"
                            rows="4">{{ old('description') }}</textarea>
                    </div>
                    @error('description')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <button class="bg-gray-600 text-white w-full p-2 rounded text-sm">Save Product</button>
                </div>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                // Ketika category berubah, update variant
                $('#category').on('change', function() {
                    let categoryId = $(this).val();
                    $('#variant').html('<option value="" disabled selected>Loading...</option>'); // Placeholder

                    if (categoryId) {
                        $.ajax({
                            url: "{{ route('get-variants') }}",
                            type: "GET",
                            data: {
                                category_id: categoryId
                            },
                            success: function(response) {
                                $('#variant').html(
                                    '<option value="" disabled selected>Select Variant</option>'
                                    ); // Reset variant
                                $.each(response.variants, function(key, variant) {
                                    $('#variant').append(
                                        `<option value="${variant.id}">${variant.name}</option>`
                                        );
                                });
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                });

                // Ketika variant dipilih, generate unique code
                $(document).ready(function() {
                    $('#category, #variant').change(function() {

                        var category_id = $('#category').val();
                        var variant_id = $('#variant').val();
                        if (category_id && variant_id) {
                            $.ajax({
                                url: "{{ route('generate-noproduct') }}",
                                type: "POST",
                                data: {
                                    category_id: category_id,
                                    variant_id: variant_id,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    console.log(response);

                                    if (response.unique_code) {
                                        $('#unique_code').val(response
                                        .unique_code); // ✅ ID sudah sesuai dengan form
                                    }
                                },
                                error: function(xhr) {
                                    console.log(xhr.responseText);
                                }
                            });
                        }
                    });
                });

            });
        </script>
    </div>
@endsection
