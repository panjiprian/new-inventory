@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form id="variant-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Add New Variant</h2>
            @csrf
            <!-- Category Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="category_id">Category</label>
                <div class="relative mt-1">
                    <select name="category_id" id="category_id"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Variant Name Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="name">Variant Name</label>
                <div class="relative mt-1">
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Enter variant name" />
                </div>
                @error('name')
                    <p class="text-red-500 text-sm mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Variant Code Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="code">Variant Code</label>
                <div class="relative mt-1">
                    <input type="text" name="code" id="code" value="{{ old('code') }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                        placeholder="Enter variant code" />
                </div>
                @error('code')
                    <p class="text-red-500 text-sm mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Save Variant
                </button>
                <a href="/varian"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("#variant-form").submit(function(event) {
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
                    url: '/input-varian',
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
                                window.location.href = "/varian";
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
        });
    </script>
@endsection
