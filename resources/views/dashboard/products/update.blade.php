@extends('layouts.main')

@section('container')
    <div class="container px-4">
        <div class="bg-white p-5 mt-5 rounded-lg">
            <div class="flex">
                <h2 class="text-gray-600 font-bold">Update Product</h2>
            </div>

            <form id="product-form">
                @csrf
                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="unique_code">Unique Code</label>
                    <div class="border-2 p-1 bg-gray-200 @error('unique_code') border-red-400 @enderror">
                        <input name="unique_code" value="{{ $product->code }}"
                            class="text-black w-full h-full focus:outline-none text-sm bg-gray-200" id="unique_code"
                            type="text" readonly>
                    </div>
                    @error('unique_code')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="name">Product Name</label>
                    <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                        <input name="name" value="{{ $product->name }}"
                            class="text-black w-full h-full focus:outline-none text-sm" id="name" type="text">
                    </div>
                    <small id="name-count" class="text-gray-500">0 characters</small>
                    @error('name')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="price">Price</label>
                    <div class="@error('price') border-red-400 @enderror border-2 p-1">
                        <input value="{{ $product->price }}" name="price"
                            class="text-black text-sm w-full h-full focus:outline-none" id="price" type="number">
                    </div>
                    <small id="price-preview" class="text-gray-500">Rp 0</small>
                    @error('price')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="image">Photo</label>
                    <div class="@error('image') border-red-400 @enderror border-2 p-1">
                        <input type="file" name="image" class="text-sm w-full h-full focus:outline-none"
                            id="image">
                            <img id="image-preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                        <p id="image-info" class="text-gray-600 mt-2"></p>
                    </div>
                    @error('image')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label class="text-sm text-gray-600" for="description">Description</label>
                    <div class="border-2 p-1 @error('description') border-red-400 @enderror">
                        <textarea name="description" class="text-black w-full h-full focus:outline-none text-sm" id="description">{{ $product->description }}</textarea>
                    </div>
                    <small id="description-count" class="text-gray-500">0 characters</small>
                    @error('description')
                        <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <button type="submit" class="bg-gray-600 text-white w-full p-2 rounded text-sm">Update Product</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Submit form
            $('#product-form').on('submit', function(e) {
                e.preventDefault();
                // Menampilkan loading spinner
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false, // Jangan biarkan menutup dengan klik luar
                    showConfirmButton: false, // Sembunyikan tombol konfirmasi
                    didOpen: () => {
                        Swal.showLoading(); // Tampilkan loading spinner
                    }

                });
                let formData = new FormData(this);
                let url = "/ubah-barang/{{ $product->id }}";

                // console.log(url);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "/barang";
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "";
                        $.each(errors, function(key, value) {
                            errorMessage += value + "<br>";
                        });
                        Swal.fire({
                            title: "Error!",
                            html: errorMessage,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });

            // Fetch categories
            $('#category').on('change', function() {
                let categoryId = $(this).val();
                let variantSelect = $('#variant');
                variantSelect.empty().append('<option value="" disabled selected>Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('get-variants') }}",
                        type: "GET",
                        data: {
                            category_id: categoryId
                        },
                        success: function(response) {
                            variantSelect.empty().append(
                                '<option value="" disabled selected>Select Variant</option>'
                            );
                            $.each(response.variants, function(key, variant) {
                                variantSelect.append(
                                    `<option value="${variant.id}">${variant.name}</option>`
                                );
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

            // Format price input
            $('#price').on('input', function() {
                let value = $(this).val();
                if (value < 0) {
                    $(this).val(0);
                }
                $('#price-preview').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value));
            });

                $('#image').change(function() {
                    // Periksa apakah ada file yang dipilih
                    if (this.files && this.files[0]) {
                        let file = this.files[0];
                        // Tampilkan informasi file
                        $('#image-info').text(
                            `File Name: ${file.name} | Size: ${(file.size / 1024).toFixed(2)} KB`);
                        // Tampilkan preview gambar
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#image-preview').attr('src', e.target.result).fadeIn();
                        };
                        reader.readAsDataURL(file);
                    }
                });

            // Live character count
            function updateCharCount(input, counter) {
                $(input).on('input', function() {
                    $(counter).text($(this).val().length + " characters");
                });
            }
            updateCharCount('#name', '#name-count');
            updateCharCount('#description', '#description-count');
        });
    </script>
@endsection
