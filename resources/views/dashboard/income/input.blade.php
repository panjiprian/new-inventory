@extends('layouts.main')

@section('container')
<div class="container mx-auto max-w-4xl px-4">
    <form id="input-income-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @csrf
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Add Receiving Product</h2>

        <!-- Product Name -->
        <div class="mb-4">
            <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700">Product Name</label>
            <select name="product_id" id="product_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">-- Select a product --</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Supplier -->
        <div class="mb-4">
            <label for="supplier_id" class="block mb-1 text-sm font-medium text-gray-700">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                <option value="">-- Select a supplier --</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            @error('supplier_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Quantity -->
        <div class="mb-4">
            <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Receiving Qty</label>
            <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            @error('quantity')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date -->
        <div class="mb-4">
            <label for="date" class="block mb-1 text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date') }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            @error('date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between gap-4 mt-6">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                Save Data
            </button>
            <a href="/barang-masuk" class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                Back
            </a>
        </div>
    </form>
</div>

<!-- jQuery for validation -->
<script>
    $(document).ready(function() {
        $("#input-income-form").submit(function(event) {
            event.preventDefault();

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

            $.ajax({
                url: '/input-barang-masuk',
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').val()
                },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "/barang-masuk";
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
                    Swal.close();
                    let errorMessages = "";
                    let response = xhr.responseJSON;
                    if (response && response.message) {
                        errorMessages = response.message;
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
