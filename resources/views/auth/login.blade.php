<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="{{ url('image/favicon.png') }}">
  <link rel="stylesheet" type="text/css" href="{{ url('css/style.css') }}">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>Login Gloglo Inventory</title>
</head>
<body class="bg-gradient-to-br from-blue-900 to-slate-700 min-h-screen flex items-center justify-center">
  <div class="bg-white p-8 rounded-2xl shadow-lg w-96">
    <div class="text-center">
      <img src="{{ asset('image/gloglo-logo.png') }}" height="100px" width="250px" class="mx-auto" alt="Gloglo Logo">
      <p class="font-bold text-2xl mt-5 text-gray-700">Welcome Back!</p>
      <p class="text-gray-500 text-sm mt-1">Log in to manage your inventory</p>
    </div>
    <div class="mt-6">
      @if (session()->has('error'))
        <div class="bg-red-500 text-white text-center py-2 rounded-md">
          <p class="text-sm">{{ session()->get('error') }}</p>
        </div>
      @endif
      <form action="{{ route('login') }}" method="post">
        @csrf
        <label class="text-gray-600 font-semibold text-sm mt-4 block">Email</label>
        <input type="email" name="email" placeholder="Enter your email"
          class="w-full mt-2 p-3 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" required>

        <label class="text-gray-600 font-semibold text-sm mt-4 block">Password</label>
        <input type="password" name="password" placeholder="Enter your password"
          class="w-full mt-2 p-3 border rounded-md focus:ring-2 focus:ring-blue-500 outline-none" required>

        <button type="submit"
          class="bg-blue-600 text-white w-full py-3 mt-5 rounded-lg hover:bg-blue-700 transition duration-300">
          Log in
        </button>
      </form>
      <p class="text-center text-gray-500 text-sm mt-4">
        Don't have an account? <a class="text-blue-600 font-semibold">Contact Admin</a>
      </p>
    </div>
  </div>
</body>
</html>
