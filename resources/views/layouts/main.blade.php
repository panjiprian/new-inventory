<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="icon" href="{{ url('image/favicon.png') }}">
   <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/base.min.css"
      />
      <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
      />
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
      <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <title>Inventory Gloglo</title>
</head>
<body class="text-black">
    <!-- Navbar -->
    <div class="bg-gray-900 h-full p-4 fixed top-0 left-0 z-50 w-64 transform -translate-x-full transition-transform duration-300" id="sidebar">
        <a href="#" class="flex items-center pb-4 border-b border-b-gray-800">
            <img src="{{ asset('image/gloglo-logo.png') }}" height="110px" width="300px" alt="">
        </a>
        <ul class="mt-4">
            <li class="mb-1 group">
                <a href="/" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
                    <i class="ri-dashboard-line mr-3 text-lg"></i>
                    <span class="text-sm">Overview</span>
                </a>
            </li>
        <li class="mb-1 group">
         <a href="/barang" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-archive-2-line mr-3 text-lg"></i>
            <span class="text-sm">Product Data</span>
         </a>
        </li>
        <li class="mb-1 group">
         <a href="/supplier" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-truck-line mr-3 text-lg"></i>
            <span class="text-sm">Supplier Data</span>
         </a>
        </li>
        <li class="mb-1 group">
         <a href="/kategori" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-file-list-3-fill mr-3 text-lg"></i>
            <span class="text-sm">Category</span>
         </a>
        </li>
        <li class="mb-1 group">
            <a href="/varian" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
               <i class="ri-store-line mr-3 text-lg"></i>
               <span class="text-sm">Variant</span>
            </a>
           </li>
        <li class="mb-1 mt-5 group">
         <a href="/barang-masuk" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-arrow-down-fill mr-3 text-lg"></i>
            <span class="text-sm">Receiving</span>
         </a>
        </li>
        <li class="mb-1 group">
         <a href="/barang-keluar" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-arrow-up-fill mr-3 text-lg"></i>
            <span class="text-sm">Dispatching</span>
         </a>
        </li>
        @if(Auth::user()->role === 'admin')
        <li class="mb-1 mt-10 group">
         <a href="/petugas" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-user-line mr-3 text-lg"></i>
            <span class="text-sm">Officer</span>
         </a>
        </li>
        <li class="mb-1 group">
         <a href="/admin" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-admin-line mr-3 text-lg"></i>
            <span class="text-sm">Admin</span>
         </a>
        </li>
        @endif
        <li class="mb-1 group mt-5">
            <a href="/" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
               <i class="ri-book-open-line text-lg mr-3"></i>
               <span class="text-sm">Manual Book</span>
            </a>
        </li>
        <li class="mb-1 group">
            <a href="/logout" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
                <i class="ri-logout-circle-line text-lg mr-3"></i>
                <span class="text-sm">Logout</span>
            </a>
        </li>
    </ul>
</div>

    <!-- Main Content -->
    <main class="md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen">
        <!-- Navbar atas -->
        <div class="bg-white py-2 px-4 flex items-center justify-between shadow-md shadow-black/5 sticky top-0 left-0 z-30">
            {{-- <button class="text-gray-700" id="menu-toggle">
                <i class="ri-menu-line text-2xl"></i>
            </button> --}}
            <div class="mr-2 flex items-center">
                <p class="text-sm text-gray-600">{{ Auth::user()->name }}</p>
            </div>
        </div>
        @yield('container')
    </main>
    {{-- <style>
        /* Fallback untuk transformasi jika Tailwind tidak bekerja */
        .-translate-x-full {
            transform: translateX(-100%);
        }
        main {
    transition: margin-left 0.3s ease-in-out; /* Animasi agar lebih smooth */
}

    </style>
    <script>
document.getElementById('menu-toggle').addEventListener('click', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('main'); // Konten utama
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full'); // Tampilkan sidebar
        mainContent.classList.add('md:ml-64'); // Tambahkan margin kiri saat sidebar ditampilkan
    } else {
        sidebar.classList.add('-translate-x-full'); // Sembunyikan sidebar
        mainContent.classList.remove('md:ml-64'); // Hilangkan margin kiri agar konten penuh
    }
});

    </script> --}}

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session("error") }}',
        timer: 3000,
        showConfirmButton: true
    });
</script>
@endif

</html>
