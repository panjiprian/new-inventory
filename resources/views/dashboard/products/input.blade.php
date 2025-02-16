@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <div class="bg-white p-6 mt-8 rounded-lg shadow-lg">
            <div class="mb-6">
                <h2 class="text-gray-700 font-bold text-lg">Input New Product</h2>
            </div>

            <form id="product-form" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column: Product Details -->
                <div class="space-y-4">
                    @csrf
                    <!-- Unique Code -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="unique_code">Unique Code</label>
                        <input type="text" name="unique_code" id="unique_code" readonly
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-700 bg-gray-100 p-2"
                            title="Generated automatically" />
                    </div>

                    <!-- Product Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="name">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-700 p-2"
                            placeholder="Enter product name" />
                        <small id="name-count" class="text-gray-500">0 characters</small>
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="price">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-gray-700 p-2"
                            placeholder="Enter price (Rp)" />
                        <small id="price-preview" class="text-gray-500">Rp 0</small>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="category">Category</label>
                        <select name="category_id" id="category"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-gray-700">
                            <option value="" selected disabled>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Variant -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="variant">Variant</label>
                        <select name="variant_id" id="variant"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-gray-700">
                            <option value="" selected disabled>Select Variant</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="description">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 text-gray-700">{{ old('description') }}</textarea>
                        <small id="description-count" class="text-gray-500">0 characters</small>
                    </div>
                </div>

                <!-- Right Column: Image Upload -->
                <div class="flex flex-col space-y-2">
                    <label class="block text-sm font-medium text-gray-700" for="image">Photo</label>
                    <label for="image"
                        class="flex items-center justify-between p-2 text-white bg-gray-600 rounded-lg cursor-pointer hover:bg-blue-700">
                        <span>Choose File</span>
                    </label>
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" />

                    <!-- Image Preview -->
                    <img id="image-preview" class="mt-4 rounded-md border border-gray-300"
                        style="max-width: 100%; display: none;" />
                    <p id="image-info" class="text-gray-600 mt-2 text-sm"></p>
                </div>


                <!-- Full Width Submit & Back Buttons -->
                <div class="col-span-1 md:col-span-2 flex gap-4">
                    <button type="submit" class="bg-blue-600 text-white w-full p-2 rounded-md shadow-sm hover:bg-blue-700">
                        Save Product
                    </button>
                    <a href="/barang"
                        class="bg-red-600 text-white w-full p-2 rounded-md shadow-sm text-center hover:bg-red-700">
                        Back
                    </a>
                </div>
            </form>
        </div>
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

            $('#image').on('change', function() {
                const file = this.files && this.files[0];
                const $imageInfo = $('#image-info');
                const $imagePreview = $('#image-preview');

                if (file) {
                    $imageInfo.text(`File Name: ${file.name} | Size: ${(file.size / 1024).toFixed(2)} KB`);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $imagePreview.attr('src', e.target.result).fadeIn();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $imagePreview.fadeOut();
                    $imageInfo.text('');
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
