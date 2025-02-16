@extends('layouts.main')

@section('container')
<div class="container mx-auto max-w-4xl px-4">
    <form id="supplier-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @csrf
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Update Supplier</h2>

        <!-- Supplier Name Field -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Supplier Name</label>
            <div class="relative mt-1">
                <input type="text" name="name" id="name" value="{{ $supplier->name }}"
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                    placeholder="Enter supplier name">
            </div>
            @error('name')
                <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address Field -->
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
            <div class="relative mt-1">
                <input type="text" name="address" id="address" value="{{ $supplier->address }}"
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror"
                    placeholder="Enter address">
            </div>
            @error('address')
                <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="relative mt-1">
                <input type="email" name="email" id="email" value="{{ $supplier->email }}"
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                    placeholder="Enter email address">
            </div>
            @error('email')
                <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone Number Field -->
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
            <div class="relative mt-1">
                <input type="text" name="phone" id="phone" value="{{ $supplier->phone }}"
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                    placeholder="Enter phone number">
            </div>
            @error('phone')
                <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between gap-4 mt-6">
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                Update Supplier
            </button>
            <a href="/supplier"
                class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                Back
            </a>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        // Handle form submission
        $('#supplier-form').on('submit', function(e) {
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
            let url = "/ubah-supplier/{{ $supplier->id }}";

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
                        window.location.href = "/supplier";
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

