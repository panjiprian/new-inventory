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
                <h2 class="text-gray-600 font-bold">Variants</h2>
                @if(Auth::user()->role === 'admin')
                <a href="/input-varian" class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1">Input Variant</a>
                @endif
                <a class="text-sm bg-gray-700 text-white inline-block mt-2 px-2 py-1" href="/excel/variants">Export Excel</a>
            </div>

            <div class="flex gap-4">
                <!-- Dropdown Filter Jumlah Data -->
                <form method="GET" action="/variants" class="flex items-center gap-2">
                    <label for="per_page" class="text-sm text-gray-600">Show</label>
                    <select id="per_page" name="per_page" class="border p-1 text-sm rounded" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </form>
                <!-- Form Pencarian -->
                <form method="GET" action="/variants" class="flex items-center gap-2">
                <div class="flex">
                <div class="border p-1 px-2 rounded-l">
                <input id="search" name="search" class="focus:outline-none text-sm" type="text" placeholder="Search Variant" value="{{ request('search') }}">
                </div>
                <button type="submit" class="text-sm bg-gray-700 p-2 rounded-r text-white h-full">Search</button>
                </div>
                </form>
            </div>
        </div>

        <table class="w-full mt-5 text-sm text-gray-600">
            <thead>
                <tr class="font-bold border-b-2 p-2">
                    <td class="p-2">No</td>
                    <td class="p-2">Variant Code</td>
                    <td class="p-2">Variant Name</td>
                    <td class="p-2">Category</td>
                    <td class="p-2">Create</td>
                    <td class="p-2">Update</td>
                    <td class="p-2">Total Products</td>
                    <td class="p-2">Action</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($variants as $variant)
                    <tr class="border-b p-2">
                        <td class="p-2">{{$loop->iteration}}</td>
                        <td class="p-2">{{$variant->code}}</td>
                        <td class="p-2">{{$variant->name}}</td>
                        <td class="p-2">{{$variant->category->name ?? '-'}}</td>
                        <td>{{ $variant->creator_name ?? '-' }}</td>
                        <td>{{ $variant->updater_name ?? '-' }}</td>
                        <td class="p-2">{{ $variant->products->count() ?? 0 }}</td>

                        @if(Auth::user()->role === 'admin')
                        <td class="p-2 flex gap-2">
                            <button data-id="{{$variant->id}}" class="btn btn-delete bg-red-500 py-1 px-4 rounded text-white">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <a href="/ubah-varian/{{$variant->id}}" class="bg-yellow-400 py-1 px-4 rounded text-white">
                                <i class="ri-edit-box-line"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginasi dengan Query Per Page -->
        <div class="mt-5">
            {{ $variants->appends(['per_page' => request('per_page'), 'search' => request('search')])->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@endsection
