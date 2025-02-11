@extends('layouts.main')

@section('container')

@if (session('message'))
   <div id="toast-container" class="hidden fixed z-50 items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded border-l-2 border-green-400 shadow top-5 right-5 dark:text-gray-400 dark:divide-gray-700 space-x dark:bg-gray-800" role="alert">
    <div class=" text-green-400 text-sm font-bold capitalize">{{session()->get('message')}}</div>
</div>
@endif
    <div class="w-full flex-wrap gap-4">
        <div class="bg-white mt-5 p-5 rounded-lg">
            <div class="flex justify-between">
                <div class="text-left">
                    <h2 class="text-gray-600 font-bold">Categories</h2>
                    @if(Auth::user()->role === 'admin')
                    <a href="/input-kategori" class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1">Input Category</a>
                    @endif
                    <a  class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1" href="/excel/categories">Export Excel</a>
                </div>
            </div>
            <div class="containerTabelCategory mt-5">
            <table id="varianTabel" class="w-full text-sm text-gray-600">
                <thead>
                    <tr class="font-bold border-b-2 p-2">
                        <td class="p-2">No</td>
                        <td class="p-2">Category Code</td>
                        <td class="p-2">Category Name</td>
                        <td class="p-2">Create</td>
                        <td class="p-2">Update</td>
                        <td class="p-2">Total Products</td>
                        <td class="p-2">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="border-b p-2">
                        <td class="p-2">{{$loop->iteration}}</td>
                        <td class="p-2">{{$category->code}}</td>
                        <td class="p-2">{{$category->name}}</td>
                        <td>{{ $category->creator_name ?? '-' }}</td>
                        <td>{{ $category->updater_name ?? '-' }}</td>
                        <td class="p-2">{{$category->products->count()}}</td>
                        @if(Auth::user()->role === 'admin')
                        <td class="p-2 flex gap-2">
                            <button data-id="{{$category->id}}" class="btn-delete-category bg-red-500 py-1 px-4 rounded text-white">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <a href="/ubah-kategori/{{$category->id}}" class="bg-yellow-400 py-1 px-4 rounded text-white">
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#varianTabel').DataTable();
        });
    </script>
    </div>
@endsection
