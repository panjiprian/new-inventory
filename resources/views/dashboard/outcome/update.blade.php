@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form id="update-outcome-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md" method="POST"
            action="/ubah-barang-keluar/{{ $productOutcome->id }}">
            @csrf
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Update Dispatching Product</h2>
            <!-- Product Name -->
            <div class="mb-4 relative">
                <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700">Product Name</label>
                <div class="relative">
                    <input type="text" id="product_search" placeholder="Search product..."
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        autocomplete="off" onfocus="showProductDropdown()" oninput="filterProductDropdown()"
                        value="{{ $productIncome->product->name ?? '' }}" />

                    <ul id="product_list"
                        class="absolute bg-white border border-gray-300 rounded-lg shadow-lg w-full mt-1 hidden max-h-60 overflow-auto z-10">
                        @foreach ($products as $product)
                            <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                onclick="selectProduct('{{ $product->id }}', '{{ $product->name }}')">
                                {{ $product->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="product_id" id="product_id" value="{{ $productIncome->product_id }}" />
                @error('product_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supplier -->
            <div class="mb-4 relative">
                <label for="supplier_id" class="block mb-1 text-sm font-medium text-gray-700">Supplier</label>
                <div class="relative">
                    <input type="text" id="supplier_search" placeholder="Search supplier..."
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        autocomplete="off" onfocus="showSupplierDropdown()" oninput="filterSupplierDropdown()"
                        value="{{ $productIncome->supplier->name ?? '' }}" />

                    <ul id="supplier_list"
                        class="absolute bg-white border border-gray-300 rounded-lg shadow-lg w-full mt-1 hidden max-h-60 overflow-auto z-10">
                        @foreach ($suppliers as $supplier)
                            <li class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                onclick="selectSupplier('{{ $supplier->id }}', '{{ $supplier->name }}')">
                                {{ $supplier->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="supplier_id" id="supplier_id" value="{{ $productIncome->supplier_id }}" />
                @error('supplier_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div class="mb-4">
                <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Dispatching Qty</label>
                <input type="number" name="quantity" id="quantity" value="{{ $productOutcome->quantity }}" min="1"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block mb-1 text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date" value="{{ $productOutcome->date }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Update Data
                </button>
                <a href="/barang-keluar"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('#update-outcome-form').on('submit', function(e) {
                e.preventDefault();

                // Tampilkan SweetAlert saat proses pengiriman data
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
                let url = "/ubah-barang-keluar/{{ $productOutcome->id }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: "Data Successfully Updated",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "/barang-keluar";
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
    <script>
        function showProductDropdown() {
            document.getElementById('product_list').classList.remove('hidden');
        }

        function hideProductDropdown() {
            document.getElementById('product_list').classList.add('hidden');
        }

        function filterProductDropdown() {
            const searchInput = document.getElementById('product_search').value.toLowerCase();
            const productItems = document.querySelectorAll('#product_list li');

            productItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchInput) ? '' : 'none';
            });
        }

        function selectProduct(productId, productName) {
            document.getElementById('product_id').value = productId;
            document.getElementById('product_search').value = productName;
            hideProductDropdown();
        }

        function showSupplierDropdown() {
            document.getElementById('supplier_list').classList.remove('hidden');
        }

        function hideSupplierDropdown() {
            document.getElementById('supplier_list').classList.add('hidden');
        }

        function filterSupplierDropdown() {
            const searchInput = document.getElementById('supplier_search').value.toLowerCase();
            const supplierItems = document.querySelectorAll('#supplier_list li');

            supplierItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchInput) ? '' : 'none';
            });
        }

        function selectSupplier(supplierId, supplierName) {
            document.getElementById('supplier_id').value = supplierId;
            document.getElementById('supplier_search').value = supplierName;
            hideSupplierDropdown();
        }

        // Sembunyikan dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            const productDropdown = document.getElementById('product_list');
            const productSearchInput = document.getElementById('product_search');

            const supplierDropdown = document.getElementById('supplier_list');
            const supplierSearchInput = document.getElementById('supplier_search');

            if (!productDropdown.contains(event.target) && event.target !== productSearchInput) {
                hideProductDropdown();
            }

            if (!supplierDropdown.contains(event.target) && event.target !== supplierSearchInput) {
                hideSupplierDropdown();
            }
        });
    </script>
@endsection
@endsection
