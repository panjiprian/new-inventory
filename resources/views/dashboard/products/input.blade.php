@extends('layouts.main')

@section('container')
    <div class="container px-4">
        <div class="bg-white p-5 mt-5 rounded-lg">
            <div class="flex">
                <h2 class="text-gray-600 font-bold">Input New Product</h2>
            </div>

            <form id="product-form">
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
                        <input type="file" name="image" class="text-sm w-full h-full focus:outline-none"
                            id="image">

                        <img id="image-preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                        <p id="image-info" class="text-gray-600 mt-2"></p>
                    </div>
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
                    <div class="border-2 p-1">
                        <textarea name="description" id="description" class="text-black w-full h-full focus:outline-none text-sm"
                            rows="4">{{ old('description') }}</textarea>
                    </div>
                    <small id="description-count" class="text-gray-500">0 characters</small>
                </div>

                <div class="mt-3">
                    <button type="submit"
                        class="bg-blue-500 text-white w-full mt-2 p-2 rounded text-sm flex items-center justify-center"
                        style="background-color: #3085d6;">Save Product</button>
                    <a class="bg-red-500 text-white w-full mt-2 p-2 rounded text-sm flex items-center justify-center"
                        href="/barang" style="background-color: #d33;">Back</a>
                </div>


            </form>

        </div>

        <script>
            $(document).ready(function() {
                $("#product-form").submit(function(event) {
                    event.preventDefault();

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

                    $.ajax({
                        url: '/input-barang/store',
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "X-CSRF-TOKEN": $('input[name="_token"]').val()
                        },
                        success: function(response) {
                            // Sembunyikan loading spinner dan tampilkan pesan sukses/error
                            Swal.close(); // Menutup SweetAlert loading spinner

                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message || "Something went wrong!",
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr) {
                            // Sembunyikan loading spinner dan tampilkan pesan error
                            Swal.close(); // Menutup SweetAlert loading spinner

                            let errorMessages = "";
                            let response = xhr.responseJSON;

                            if (response && response.message) {
                                errorMessages = response
                                    .message; // Menampilkan pesan error dari server
                            } else if (response && response.errors) {
                                $.each(response.errors, function(key, value) {
                                    errorMessages += value[0] + "\n";
                                });
                            } else {
                                errorMessages = "Failed to submit data!";
                            }

                            Swal.fire({
                                title: "Error!",
                                text: errorMessages,
                                icon: "error"
                            });
                        }
                    });
                });
                // Update variants on category change
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

                // Generate unique code on category & variant change
                $('#category, #variant').change(function() {
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
                            success: function(response) {
                                if (response.unique_code) {
                                    uniqueCodeInput.hide().val(response.unique_code).fadeIn(500)
                                        .removeClass('text-gray-400');
                                }
                            },
                            error: function() {
                                uniqueCodeInput.val('Error Generating Code').addClass(
                                    'text-red-500');
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
        <script></script>
    </div>
@endsection
