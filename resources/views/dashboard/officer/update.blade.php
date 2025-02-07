@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Update Officer</h2>
        </div>

        <form action="/ubah-petugas/{{$officer->id}}" method="POST" class="w-1/2 mt-5">
            @csrf
            @method('PUT') <!-- Gunakan PUT untuk update -->

            <!-- Name -->
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">officer Name</label>
                <div class="border-2 p-1 @error('name') border-red-400 @enderror">
                    <input autocomplete="off" name="name" value="{{$officer->name}}" class="w-full h-full focus:outline-none text-sm" id="name" type="text">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="email">Email</label>
                <div class="@error('email') border-red-400 @enderror border-2 p-1">
                    <input autocomplete="off" type="email" value="{{$officer->email}}" name="email" class="text-sm w-full h-full focus:outline-none" id="email">
                </div>
                @error('email')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="phone">Phone</label>
                <div class="@error('phone') border-red-400 @enderror border-2 p-1">
                    <input autocomplete="off" type="tel" name="phone" id="phone" class="text-sm w-full h-full focus:outline-none" value="{{$officer->phone ?? '+62'}}">
                </div>
                @error('phone')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <!-- Password (Opsional) -->
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="password">New Password (Leave blank if not changing)</label>
                <div class="@error('password') border-red-400 @enderror border-2 p-1">
                    <input autocomplete="off" type="password" name="password" class="text-sm w-full h-full focus:outline-none" id="password">
                </div>
                @error('password')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-3">
                <button class="bg-gray-600 w-full text-white p-2 rounded text-sm">Update Data</button>
            </div>
        </form>

        <!-- 🔥 Tambahkan JavaScript di Bawah -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let phoneInput = document.getElementById("phone");

                // Pastikan defaultnya selalu "+62"
                if (!phoneInput.value.startsWith("+62")) {
                    phoneInput.value = "+62";
                }

                phoneInput.addEventListener("input", function () {
                    // Cegah user menghapus "+62"
                    if (!this.value.startsWith("+62")) {
                        this.value = "+62";
                    }

                    // Hanya izinkan angka setelah "+62"
                    this.value = this.value.replace(/[^0-9+]/g, "");
                });

                phoneInput.addEventListener("focus", function () {
                    // Jika cuma "+62", otomatis geser kursor biar user langsung input angka
                    if (this.value === "+62") {
                        this.setSelectionRange(3, 3);
                    }
                });
            });
        </script>


    </div>
</div>
@endsection

