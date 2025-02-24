<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ url('image/favicon.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/base.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Flowbite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Remixicon CSS -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <!-- Chart.js Integration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Moment.js (dependensi Daterangepicker) -->
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>

    <!-- Daterangepicker -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.tailwind.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.tailwind.min.css" rel="stylesheet">


    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Inventory Gloglo</title>
</head>

<body class="text-black">
    <div class="w-64 bg-gray-900 h-full p-4 fixed top-0 left-0 shadow-lg">
        <a href="#" class="flex items-center pb-4 border-b border-gray-800">
            <img src="{{ asset('image/gloglo-logo.png') }}" class="h-14 w-auto" alt="Logo">
        </a>

        <ul class="mt-4">
            <li class="mb-1">
                <a href="/"
                    class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-dashboard-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Overview</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="/barang"
                    class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-archive-2-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Product Data</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="/supplier"
                class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-truck-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Supplier Data</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="/kategori"
                    class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-file-list-2-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Category</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="/varian"
                    class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-file-list-3-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Variant</span>
                </a>
            </li>
            <li class="mb-1 mt-5 relative">
                <button type="button" class="flex items-center justify-between w-full py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out" id="transaction-menu">
                    <span class="flex items-center">
                        <i class="ri-exchange-line mr-3 text-lg"></i>
                        <span class="text-sm font-medium">Transaction Product</span>
                    </span>
                    <i class="ri-arrow-down-s-line"></i>
                </button>
                <ul class="hidden flex-col mt-2 bg-gray-800 rounded-lg" id="transaction-dropdown">
                    <li class="mb-1">
                        <a href="/barang-masuk"
                            class="block py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                            Receiving
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="/barang-keluar"
                            class="block py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                            Dispatching
                        </a>
                    </li>
                    <li class="mb-1">
                        <a href="/laporan"
                            class="block py-2 px-4 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                            Report
                        </a>
                    </li>
                </ul>
            </li>
            @if (Auth::user()->role === 'admin')
                <li class="mb-1 mt-5">
                    <a href="/petugas"
                        class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                        <i class="ri-user-line mr-3 text-lg"></i>
                        <span class="text-sm font-medium">Officer</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="/admin"
                        class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                        <i class="ri-admin-line mr-3 text-lg"></i>
                        <span class="text-sm font-medium">Admin</span>
                    </a>
                </li>
            @endif
            <li class="mb-1 mt-5">
                <a href="{{ asset('storage/manuals/manual_book.pdf') }}" target="_blank"
                    class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition duration-200 ease-in-out">
                    <i class="ri-book-read-line mr-3 text-lg"></i>
                    <span class="text-sm font-medium">Manual Book</span>
                </a>
            </li>

        </ul>
    </div>

    <!-- Main Content -->
    <main class="md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen">
        <div class="bg-white py-2 px-4 flex items-center justify-between shadow-md shadow-black/5 border-b sticky top-0 left-0 z-30">
            <div class="mr-2 flex items-center">
                <i class="ri-user-line text-gray-600 mr-2"></i>
                <p class="text-sm text-gray-600">{{ Auth::user()->name }}</p>
            </div>
            <div>
                <a href="/logout" class="text-sm bg-gray-700 text-white py-1 px-3 rounded-lg hover:bg-gray-800 transition duration-200 ease-in-out">Logout</a>
            </div>
        </div>
        @yield('container')
    </main>
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>
<script>
    document.getElementById('transaction-menu').addEventListener('click', function() {
        const dropdown = document.getElementById('transaction-dropdown');
        dropdown.classList.toggle('hidden');
    });
</script>
</html>
