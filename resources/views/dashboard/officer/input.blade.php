@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Input Officer</h2>
        </div>

        <form action="/input-petugas" method="POST" class="w-1/2 mt-5">
            @csrf
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Officer Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input autocomplete="off" name="name" value="{{ old('name') }}"
                        class="w-full h-full focus:outline-none text-sm" id="name" type="text" required>
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="email">Email</label>
                <div class="@error('email') border-red-400 @enderror border-2 p-1">
                    <input autocomplete="off" type="email" value="{{ old('email') }}"
                        name="email" class="text-sm w-full h-full focus:outline-none" id="email" required>
                </div>
                @error('email')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="phone">Phone Number</label>
                <div class="border-2 p-1 @error('phone') border-red-400 @enderror">
                    <input autocomplete="off" type="tel" name="phone" id="phone" class="text-sm w-full h-full focus:outline-none" value="+62">
            <script>
                document.getElementById("phone").addEventListener("input", function (e) {
                    if (!e.target.value.startsWith("+62")) {
                        e.target.value = "+62";
                    }
                });
            </script>
                </div>
                @error('phone')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <div class="mt-3">
                <label class="text-sm text-gray-600" for="password">Password</label>
                <div class="@error('password') border-red-400 @enderror border-2 p-1">
                    <input autocomplete="off" type="password" name="password"
                        class="text-sm w-full h-full focus:outline-none" id="password" required>
                </div>
                @error('password')
                    <p class="italic text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hidden input untuk role -->
            <input type="hidden" name="role" value="officer">

            <div class="mt-3">
                <button class="bg-gray-600 w-full text-white p-2 rounded text-sm">Save Data</button>
            </div>
        </form>

    </div>
</div>
@endsection

