@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <div class="bg-white p-6 mt-8 rounded-lg shadow-lg">
            <div class="mb-6">
                <h2 class="text-gray-700 font-bold text-lg">Update Product</h2>
            </div>

            <form id="product-form" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Bagian Kiri: Data Produk -->
                @csrf
                <div>
                    <!-- Unique Code -->
                    <div class="mb-4">
                        <label for="unique_code" class="block text-sm font-medium text-gray-600">Unique Code</label>
                        <input type="text" name="unique_code" id="unique_code" value="{{ $product->code }}"
                            class="w-full p-2 mt-1 bg-gray-200 border border-gray-300 rounded-md text-gray-700 focus:outline-none"
                            readonly>
                    </div>

                    <!-- Product Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-600">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ $product->name }}"
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring focus:ring-gray-400">
                        <small id="name-count" class="text-gray-500">0 characters</small>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-600">Price</label>
                        <input type="number" name="price" id="price" value="{{ $product->price }}"
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md text-gray-700 focus:outline-none">
                        <small id="price-preview" class="text-gray-500">Rp 0</small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-600">Description</label>
                        <textarea name="description" id="description"
                            class="w-full p-2 mt-1 border border-gray-300 rounded-md text-gray-700 focus:outline-none">{{ $product->description }}</textarea>
                        <small id="description-count" class="text-gray-500">0 characters</small>
                    </div>
                </div>

                <div class="flex flex-col space-y-2">
                    <label class="block text-sm font-medium text-gray-700" for="image">Photo</label>
                    <label for="image"
                        class="flex items-center justify-between p-2 text-white bg-gray-600 rounded-lg cursor-pointer hover:bg-blue-700">
                        <span>Choose File</span>
                    </label>
                    <!-- Input File -->
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" />

                    <!-- Image Preview -->
                    <img id="image-preview" class="mt-4 rounded-md border border-gray-300"
                        style="max-width: 100%; display: none;" />
                    <p id="image-info" class="text-gray-600 mt-2 text-sm"></p>
                </div>


                <!-- Submit Button (diletakkan di bawah untuk kedua kolom) -->
                <div class="col-span-2 mt-6">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#product-form').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                let formData = new FormData(this);
                let url = "/ubah-barang/{{ $product->id }}";

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

            $('#image').change(function() {
                if (this.files && this.files[0]) {
                    let file = this.files[0];

                    // Tampilkan nama dan ukuran file
                    $('#image-info').text(
                        `File Name: ${file.name} | Size: ${(file.size / 1024).toFixed(2)} KB`);

                    // Tampilkan preview gambar
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview')
                            .attr('src', e.target.result)
                            .css('display', 'block') // Pastikan ditampilkan
                            .fadeIn();
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

            // Price format preview
            $('#price').on('input', function() {
                let value = $(this).val();
                if (value < 0) $(this).val(0);
                $('#price-preview').text(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(value));
            });
        });
    </script>
@endsection
