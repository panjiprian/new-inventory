@extends('layouts.main')

@section('container')
    @if (session('message'))
        <div id="toast-container"
            class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800"
            role="alert">
            <div class=" text-green-400 text-sm font-bold capitalize">{{ session()->get('message') }}</div>
        </div>
    @endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg">
            <div class="flex justify-between">
                <div class="text-left">
                    <h2 class="text-gray-600 font-bold">Product Data</h2>
                    @if (Auth::user()->role === 'admin')
                        <a href="/input-barang" class="text-sm inline-block bg-gray-700 text-white mt-2 px-2 py-1">Input
                            Product</a>
                    @endif
                    <a class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1" href="/excel/products">Export
                        Excel</a>
                </div>
                <form method="get" action="/barang" class="form">
                    <div class="border p-1 px-2 rounded flex items-center gap-2">
                        <input id="from_date" name="from_date" type="date" class="focus:outline-none text-sm w-20">
                        <input id="to_date" name="to_date" type="date" class="focus:outline-none text-sm w-20">
                        <input id="search" name="search" class="focus:outline-none text-sm w-40" type="text"
                            placeholder="Search Product">
                        <button type="submit" class="text-sm bg-gray-700 p-2 rounded text-white">Search</button>
                    </div>
                </form>
            </div>

            <table id="product-table" class="w-full mt-5 text-sm text-gray-600">
                <thead>
                    <tr class="font-bold border-b-2 p-2">
                        <td class="p-2">No</td>
                        <td class="p-2">Product Code</td>
                        <td class="p-2">Product Name</td>
                        <td class="p-2">Price</td>
                        <td class="p-2">Current Stock</td>
                        <td class="p-2">Create</td>
                        <td class="p-2">Update</td>
                        <td class="p-2">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b p-2">
                            <td class="p-2">{{ $loop->iteration }}</td>
                            <td class="p-2">{{ $product->code }}</td>
                            <td class="p-2">{{ $product->name }}</td>
                            <td class="p-2">Rp.{{ number_format($product->price, 0) }}</td>
                            <td class="p-2">{{ $product->stock }}</td>
                            <td class="p-2">
                                {{ $product->createdBy->name ?? '-' }} ({{ $product->created_at->format('d M Y') }})
                            </td>
                            <td class="p-2">
                                @if ($product->updatedBy && $product->updatedBy->name)
                                    {{ $product->updatedBy->name }} ({{ $product->updated_at->format('d M Y') }})
                                @else
                                    -
                                @endif
                            </td>
                            @if (Auth::user()->role === 'admin')
                                <td class="p-2 flex gap-2">
                                    <button data-id="{{ $product->id }}"
                                        class="btn-delete-product bg-red-500 py-1 px-4 rounded text-white">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                    <a href="/ubah-barang/{{ $product->id }}"
                                        class="bg-yellow-400 py-1 px-4 rounded text-white">
                                        <i class="ri-edit-box-line"></i>
                                    </a>
                                    <button data-modal-target="default-modal" data-modal-toggle="default-modal"
                                        data-code="{{ $product->code }}" data-name="{{ $product->name }}"
                                        data-description="{{ $product->description }}"
                                        data-price="Rp.{{ number_format($product->price, 0) }}"
                                        data-category="{{ $product->category->name ?? '-' }}"
                                        data-variant="{{ $product->variant->name ?? '-' }}"
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png') }}"
                                        class="bg-blue-400 py-1 px-4 rounded text-white" type="button">
                                        <i class="ri-eye-line"></i>
                                    </button>

                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div id="default-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-black">
                            Detail Product
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 flex flex-col md:flex-row gap-4">
                        <!-- Kolom gambar -->
                        <div class="w-full md:w-1/3">
                            <p><strong>Product Image:</strong></p>
                            <div id="modal-product-image" class="mt-2">
                                <img src="" alt="Product Image" class="w-full h-auto rounded">
                            </div>
                        </div>

                        <!-- Kolom detail produk -->
                        <div class="w-full md:w-2/3 space-y-4">
                            <p><strong>Product Code:</strong> <span id="modal-product-code"></span></p>
                            <p><strong>Product Name:</strong> <span id="modal-product-name"></span></p>
                            <p><strong>Description:</strong> <span id="modal-product-description"></span></p>
                            <p><strong>Price:</strong> <span id="modal-product-price"></span></p>
                            <p><strong>Category:</strong> <span id="modal-product-category"></span></p>
                            <p><strong>Variant:</strong> <span id="modal-product-variant"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                $('#product-table').DataTable({
                    "pagingType": "simple", // Bisa gunakan simple, numbers, atau full_numbers
                    "language": {
                        "paginate": {
                            "previous": "←",
                            "next": "→"
                        }
                    }
                });
            });
            $('[data-modal-toggle="default-modal"]').on('click', function() {
                // Ambil data dari tombol
                const productCode = $(this).data('code');
                const productName = $(this).data('name');
                const productDescription = $(this).data('description');
                const productPrice = $(this).data('price');
                const productCategory = $(this).data('category');
                const productVariant = $(this).data('variant');
                const productImage = $(this).data('image');

                // Isi modal dengan data yang diambil
                $('#modal-product-code').text(productCode);
                $('#modal-product-name').text(productName);
                $('#modal-product-description').text(productDescription);
                $('#modal-product-price').text(productPrice);
                $('#modal-product-category').text(productCategory);
                $('#modal-product-variant').text(productVariant);
                $('#modal-product-image').html(
                    `<img src="${productImage}" alt="Product Image" class="w-32 h-auto mt-4 rounded">`);
            });
            // Event untuk menampilkan modal detail
            $('.btn-detail-product').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('id');

                // AJAX request untuk mendapatkan detail produk
                $.ajax({
                    url: `/api/get-product-detail/${productId}`,
                    type: 'GET',
                    success: function(data) {
                        // Isi konten modal dengan data produk
                        $('#modal-content').html(`
                        <p><strong>Product Code:</strong> ${data.code}</p>
                        <p><strong>Product Name:</strong> ${data.name}</p>
                        <p><strong>Description:</strong> ${data.description}</p>
                        <p><strong>Price:</strong> Rp. ${parseInt(data.price).toLocaleString()}</p>
                        <p><strong>Stock:</strong> ${data.stock}</p>
                        <p><strong>Category:</strong> ${data.category}</p>
                        <p><strong>Variant:</strong> ${data.variant}</p>
                        <img src="${data.image}" alt="Product Image" class="w-32 h-auto mt-4 rounded">
                    `);

                        // Tampilkan modal
                        $('#product-detail-modal').removeClass('hidden');
                    },
                    error: function() {
                        alert('Failed to load product details.');
                    }
                });
            });

            // Event untuk menutup modal
            $('#close-modal').on('click', function() {
                $('#product-detail-modal').addClass('hidden');
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let toast = document.getElementById("toast-container");
            if (toast) {
                toast.classList.remove("hidden");
                setTimeout(() => {
                    toast.classList.add("hidden");
                }, 3000);
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".btn-delete-product").forEach(button => {
                button.addEventListener("click", function() {
                    let productId = this.getAttribute("data-id");
                    if (confirm("Are you sure you want to delete this product?")) {
                        fetch(`/delete-product/${productId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    alert("Product deleted successfully!");
                                    location.reload();
                                } else {
                                    alert("Failed to delete product!");
                                }
                            })
                            .catch(error => console.error(error));
                    }
                });
            });
        });
    </script>
@endsection
