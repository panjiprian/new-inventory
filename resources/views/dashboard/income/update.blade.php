@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form id="update-income-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Update Receiving Product</h2>

            <!-- Product Name -->
            <div class="mb-4">
                <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700">Product Name</label>
                <select name="product_id" id="product_id"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">-- Select a product --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $productIncome->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Supplier -->
            <div class="mb-4">
                <label for="supplier_id" class="block mb-1 text-sm font-medium text-gray-700">Supplier</label>
                <select name="supplier_id" id="supplier_id"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">-- Select a supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $productIncome->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Receiving Qty</label>
                <input type="number" name="quantity" id="quantity" value="{{ $productIncome->quantity }}" min="1"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block mb-1 text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date" value="{{ $productIncome->date }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Update Data
                </button>
                <a href="/barang-masuk"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#update-income-form').on('submit', function(e) {
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
                let url = "/ubah-barang-masuk/{{ $productIncome->id }}";

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
                            window.location.href = "/barang-masuk";
                        });
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
                            errorMessages = "Failed to update data!";
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
