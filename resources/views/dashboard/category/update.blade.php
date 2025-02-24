@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form id="category-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Update Category</h2>

            <!-- Category Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="name">Category Name</label>
                <div class="relative mt-1">
                    <input type="text" name="name" id="name" value="{{ $category->name }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Enter category name" />
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Code Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="code">Category Code</label>
                <div class="relative mt-1">
                    <input type="text" name="code" id="code" value="{{ $category->code }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                        placeholder="Enter category code" />
                </div>
                @error('code')
                    <p class="text-red-500 text-sm mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Update Category
                </button>
                <a href="/kategori"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>


    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#category-form').on('submit', function(e) {
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
                let url = "/ubah-kategori/{{ $category->id }}";

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
                            window.location.href = "/kategori";
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
        });
    </script>
@endsection
