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
                    <div class="border-2 p-1">
                        <input name="unique_code" id="unique_code" value=""
                            class="text-black w-full h-full focus:outline-none text-sm" type="text" readonly
                            title="Generated automatically">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="name">Product Name</label>
                    <div class="border-2 p-1">
                        <input name="name" value="{{ old('name') }}"
                            class="text-black w-full h-full focus:outline-none text-sm" id="name" type="text"
                            placeholder="Enter product name">
                    </div>
                    <small id="name-count" class="text-gray-500">0 characters</small>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="price">Price</label>
                    <div class="border-2 p-1">
                        <input value="{{ old('price') }}" name="price"
                            class="text-black text-sm w-full h-full focus:outline-none" id="price" type="number"
                            placeholder="Enter price (Rp)">
                    </div>
                    <small id="price-preview" class="text-gray-500">Rp 0</small>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="image">Photo</label>
                    <div class="border-2 p-1">
                        <input type="file" name="image" class="text-sm w-full h-full focus:outline-none" id="image">
                        <img id="image-preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                    </div>
                </div>

                <div class="flex gap-1 mt-3">
                    <div class="w-full">
                        <label class="text-sm text-gray-600" for="category">Category</label>
                        <div class="border">
                            <select name="category_id" class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="category">
                                <option value="" selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option class="text-sm" value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <select name="variant_id" class="w-full text-black p-2 text-sm bg-transparent focus:outline-none" id="variant">
                                <option value="" selected disabled>Select Variant</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="description">Description</label>
                    <div class="border-2 p-1">
                        <textarea name="description" id="description" class="text-black w-full h-full focus:outline-none text-sm"
                            rows="4">{{ old('description') }}</textarea>
                    </div>
                    <small id="description-count" class="text-gray-500">0 characters</small>
                </div>

                <div class="mt-3">
                    <button class="bg-gray-600 text-white w-full p-2 rounded text-sm">Save Product</button>
                </div>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Update variants on category change
                $('#category').on('change', function() {
                    let categoryId = $(this).val();
                    let variantSelect = $('#variant');
                    variantSelect.empty().append('<option value="" disabled selected>Loading...</option>');

                    if (categoryId) {
                        $.ajax({
                            url: "{{ route('get-variants') }}",
                            type: "GET",
                            data: { category_id: categoryId },
                            success: function(response) {
                                variantSelect.empty().append('<option value="" disabled selected>Select Variant</option>');
                                $.each(response.variants, function(key, variant) {
                                    variantSelect.append(`<option value="${variant.id}">${variant.name}</option>`);
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to fetch variants. Please try again.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        });
                    }
                });

                // Generate unique code on category & variant change
                $('#category, #variant').change(function () {
                    let category_id = $('#category').val();
                    let variant_id = $('#variant').val();
                    let uniqueCodeInput = $('#unique_code');

                    if (category_id && variant_id) {
                        uniqueCodeInput.val('Generating...').addClass('text-gray-400');
                        $.ajax({
                            url: "{{ route('generate-noproduct') }}",
                            type: "POST",
                            data: {
                                category_id: category_id,
                                variant_id: variant_id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.unique_code) {
                                    uniqueCodeInput.hide().val(response.unique_code).fadeIn(500).removeClass('text-gray-400');
                                }
                            },
                            error: function () {
                                uniqueCodeInput.val('Error Generating Code').addClass('text-red-500');
                            }
                        });
                    }
                });

                // Format price input
                $('#price').on('input', function () {
                    let value = $(this).val();
                    if (value < 0) { $(this).val(0); }
                    $('#price-preview').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value));
                });

                // Image preview
                $('#image').change(function () {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#image-preview').attr('src', e.target.result).fadeIn();
                    };
                    reader.readAsDataURL(this.files[0]);
                });

                // Live character count
                function updateCharCount(input, counter) {
                    $(input).on('input', function () {
                        $(counter).text($(this).val().length + " characters");
                    });
                }
                updateCharCount('#name', '#name-count');
                updateCharCount('#description', '#description-count');
            });
        </script>
    </div>
@endsection
