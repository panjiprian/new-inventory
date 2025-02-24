@extends('layouts.main')

@section('container')
<div class="container mx-auto max-w-4xl px-4">
    <form id="input-dispatch-form" action="/input-barang-keluar" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
        @csrf
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Input Dispatching Product</h2>

        <!-- Product Name -->
            <!-- Product Name -->
            <div class="mb-4 relative">
                <label for="product_id" class="block mb-1 text-sm font-medium text-gray-700">Product Name</label>
                <div class="relative">
                    <input type="text" id="product_search" placeholder="Search product..."
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm"
                        autocomplete="off" onfocus="showDropdown()" oninput="filterDropdown()" />

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
                <input type="hidden" name="product_id" id="product_id" />
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
                        autocomplete="off" onfocus="showSupplierDropdown()" oninput="filterSupplierDropdown()" />

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
                <input type="hidden" name="supplier_id" id="supplier_id" />
                @error('supplier_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

        <!-- Dispatching Qty -->
        <div class="mb-4">
            <label for="quantity" class="block mb-1 text-sm font-medium text-gray-700">Dispatching Qty</label>
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
            <a href="/barang-keluar" class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                Back
            </a>
        </div>
    </form>
</div>

<!-- jQuery for validation -->
<script>
    $(document).ready(function() {
        $("#input-dispatch-form").submit(function(event) {
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
                url: '/input-barang-keluar',
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
                            window.location.href = "/barang-keluar";
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
@section('js')
    <script>
        function showDropdown() {
            document.getElementById('product_list').classList.remove('hidden');
        }

        function hideDropdown() {
            document.getElementById('product_list').classList.add('hidden');
        }

        function filterDropdown() {
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
            hideDropdown();
        }

        // Sembunyikan dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('product_list');
            const searchInput = document.getElementById('product_search');
            if (!dropdown.contains(event.target) && event.target !== searchInput) {
                hideDropdown();
            }
        });
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

            // Sembunyikan dropdown supplier saat klik di luar
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('supplier_list');
                const searchInput = document.getElementById('supplier_search');
                if (!dropdown.contains(event.target) && event.target !== searchInput) {
                    hideSupplierDropdown();
                }
            });
    </script>
@endsection
@endsection
