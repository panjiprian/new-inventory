@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form action="/input-petugas" method="POST" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Add New Officer</h2>
            <!-- Officer Name -->
            <div class="mb-4 relative">
                <label for="name" class="block text-sm font-medium text-gray-700">Officer Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                    class="mt-1 block w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-400 @enderror"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4 relative">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    class="mt-1 block w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-400 @enderror"
                    required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="mb-4 relative">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="+62"
                    class="mt-1 block w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-400 @enderror">
                <script>
                    document.getElementById("phone").addEventListener("input", function(e) {
                        if (!e.target.value.startsWith("+62")) {
                            e.target.value = "+62";
                        }
                    });
                </script>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password"
                    class="mt-1 block w-full p-2 border rounded-md focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-400 @enderror"
                    required>
                @error('password')
                    <p class="text-red-500 text-xs mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hidden Role -->
            <input type="hidden" name="role" value="officer">

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Save Officer
                </button>
                <a href="/petugas"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let phoneInput = document.getElementById("phone");

            if (!phoneInput.value.startsWith("+62")) {
                phoneInput.value = "+62";
            }

            phoneInput.addEventListener("input", function() {
                if (!this.value.startsWith("+62")) {
                    this.value = "+62";
                }
                this.value = this.value.replace(/[^0-9+]/g, "");
            });

            phoneInput.addEventListener("focus", function() {
                if (this.value === "+62") {
                    this.setSelectionRange(3, 3);
                }
            });
        });
    </script>
@endsection
