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
      <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
    <title>Inventory Gloglo</title>
</head>
<body class="text-black">

    {{-- sidebar start --}}
   <div class="w-64 bg-gray-900 h-full p-4 fixed top-0 left-0">
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
            <i class="ri-store-line mr-3 text-lg"></i>
            <span class="text-sm">Category</span>
         </a>
        </li>
        <li class="mb-1 mt-5 group">
         <a href="/barang-masuk" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-file-add-fill mr-3 text-lg"></i>
            <span class="text-sm">Receiving</span>
         </a>
        </li>

        <li class="mb-1 group">
         <a href="/barang-keluar" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-folder-reduce-fill mr-3 text-lg"></i>
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
               <i class="ri-book-read-line text-lg mr-3"></i>
               <span class="text-sm">Manual Book</span>
            </a>
           </li>
         <li class="mb-1 group mt-5">
         <a href="/logout" class="flex items-center py-2 px-4 text-gray-300 hover:bg-gray-600 rounded-lg">
            <i class="ri-logout-circle-line text-lg mr-3"></i>
            <span class="text-sm">Logout</span>
         </a>
        </li>
      </ul>
   </div>
   {{-- sidebar end --}}

   <main class="md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen">

    {{-- navbar start --}}
    <div class="bg-white py-2 px-4 flex items-center justify-between shadow-md shadow-black/5 sticky top-0 left-0 z-30">
        <div class="mr-2 flex items-center">
            <p class="text-sm text-gray-600">{{Auth::user()->name}}</p>
        </div>
    </div>

    {{-- navbar end --}}

    {{-- main content --}}
    @yield('container')
    {{-- main content end --}}
</main>
    <script src="{{ asset('js/index.js') }}"></script>
    @yield('js')
</body>
</html>
