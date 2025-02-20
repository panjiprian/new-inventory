@extends('layouts.main')

@section('container')
    @if (session('message'))
        <div id="toast-container"
            class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
            role="alert">
            <div class="text-green-400 text-sm font-bold capitalize">{{ session()->get('message') }}</div>
        </div>
    @endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg shadow-lg">
            <!-- Header Section -->
            <div class="flex justify-between items-center">
                <div class="text-left">
                    <h2 class="text-gray-800 font-bold text-lg">Receiving Products</h2>
                    <a href="/input-barang-masuk"
                        class="text-sm bg-blue-600 text-white inline-block mt-2 px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Input Receiving Product
                    </a>
                    <a href="/excel/products-income"
                        class="text-sm bg-green-600 text-white inline-block mt-2 px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                        Export Excel
                    </a>
                </div>
            </div>

            <!-- Table Section -->
            <div class="containerTabelProductIncome mt-5 overflow-x-auto">
                <table id="productIncomeTable"
                    class="min-w-full text-sm text-left text-gray-700 border-collapse border border-gray-200">
                    <thead>
                        <tr class="font-bold bg-gray-100 text-gray-700 border-b-2 border-gray-300">
                            <th class="p-2 border border-gray-300">No</th>
                            <th class="p-2 border border-gray-300">Product Name</th>
                            <th class="p-2 border border-gray-300">User</th>
                            <th class="p-2 border border-gray-300">Supplier</th>
                            <th class="p-2 border border-gray-300">Receiving Qty</th>
                            <th class="p-2 border border-gray-300">Date</th>
                            <th class="p-2 border border-gray-300">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productsIncome as $productIncome)
                            <tr class="border-b border-gray-300">
                                <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                                <td class="p-2 border border-gray-300">{{ $productIncome->product->name }}</td>
                                <td class="p-2 border border-gray-300">{{ $productIncome->user->name }}</td>
                                <td class="p-2 border border-gray-300">{{ $productIncome->supplier->name }}</td>
                                <td class="p-2 border border-gray-300">{{ $productIncome->quantity }}</td>
                                <td class="p-2 border border-gray-300">{{ $productIncome->date }}</td>
                                <td class="p-2 flex justify-center items-center gap-2 border border-gray-300">
                                    <button data-id="{{ $productIncome->id }}"
                                        class="btn-delete-product-income bg-red-600 py-1 px-4 rounded text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <a href="/ubah-barang-masuk/{{ $productIncome->id }}"
                                        class="bg-yellow-500 py-1 px-4 rounded text-white hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- DataTables Script -->
        <script>
            $(document).ready(function() {
                $('#productIncomeTable').DataTable({
                    "paging": true, // Mengaktifkan pagination
                    "searching": true, // Mengaktifkan pencarian
                    "lengthChange": false, // Menonaktifkan opsi untuk mengubah jumlah data per halaman
                    "pageLength": 10, // Jumlah data per halaman default
                    "info": false, // Menyembunyikan informasi total data
                    "responsive": true, // Menambahkan responsivitas
                    "order": [
                        [0, "asc"]
                    ], // Sorting default berdasarkan kolom kedua (Product Name)
                    "language": {
                        "emptyTable": "No product income available", // Pesan jika tabel kosong
                        "search": "Search Receiving Products:" // Placeholder pencarian
                    }
                });
            });
        </script>
    </div>
@endsection
