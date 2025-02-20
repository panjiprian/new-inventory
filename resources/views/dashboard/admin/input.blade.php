@extends('layouts.main')

@section('container')
    <div class="container mx-auto max-w-4xl px-4">
        <form id="admin-form" class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
            @csrf
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Add New Admin</h2>

            <!-- Admin Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="name">Admin Name</label>
                <div class="relative mt-1">
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="Enter admin name" />
                </div>
                <p class="text-red-500 text-sm mt-1 italic error-message" id="error-name"></p>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                <div class="relative mt-1">
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                        placeholder="Enter email" />
                </div>
                <p class="text-red-500 text-sm mt-1 italic error-message" id="error-email"></p>
            </div>

            <!-- Phone Number -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="phone">Phone Number</label>
                <div class="relative mt-1">
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', '+62') }}"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" />
                </div>
                <p class="text-red-500 text-sm mt-1 italic error-message" id="error-phone"></p>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                <div class="relative mt-1">
                    <input type="password" name="password" id="password"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                        placeholder="Enter password" />
                </div>
                <p class="text-red-500 text-sm mt-1 italic error-message" id="error-password"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between gap-4 mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Save Admin
                </button>
                <a href="/admin"
                    class="w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    Back
                </a>
            </div>
        </form>
    </div>

    <!-- jQuery & SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $("#admin-form").submit(function (event) {
                event.preventDefault(); // Mencegah reload halaman

                // Menampilkan loading spinner
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
                    url: '/input-admin',
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        Swal.close(); // Tutup SweetAlert loading spinner

                        if (response.success) {
                            Swal.fire({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.href = "/admin";
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message || "Something went wrong!",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.close(); // Tutup loading spinner

                        $(".error-message").text(""); // Bersihkan pesan error sebelumnya
                        let response = xhr.responseJSON;

                        if (response && response.errors) {
                            $.each(response.errors, function (key, value) {
                                $("#error-" + key).text(value[0]); // Tampilkan error di bawah input terkait
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to submit data!",
                                icon: "error"
                            });
                        }
                    }
                });
            });

            // Memastikan input phone number selalu diawali "+62"
            $("#phone").on("input", function () {
                if (!$(this).val().startsWith("+62")) {
                    $(this).val("+62");
                }
            });
        });
    </script>
@endsection
