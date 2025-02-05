@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Update Dispatching Product</h2>
        </div>

        <form action="/ubah-barang-keluar/{{$productOutcome->id}}" method="POST" class="w-1/2 mt-5">
            @csrf
            <div class="flex gap-1 mt-3">
                <div class="w-full">
                    <label class="text-sm text-gray-600"  for="name">Product Name</label>
                    <div class="border">
                        {{-- select with choice js --}}
                        <select name="product_id" data-id-product="{{$productOutcome->product_id}}"  class="select-product text-black" id="">
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex gap-1 mt-3">
                <div class="w-full">
                    <label class="text-sm text-gray-600"  for="category">Supplier</label>
                    <div class="border">
                        {{-- select with choice js --}}
                        <select data-id-supplier="{{$productOutcome->supplier_id}}" name="supplier_id" class="select-supplier text-black" id="">
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="quantity">Dispatching Qty</label>
                <div class="@error('quantity')  border-red-400  @enderror border-2 p-1">
                    <input value="{{$productOutcome->quantity}}" name="quantity" autocomplete="off" class="text-sm text-black w-full h-full focus:outline-none" id="quantity" type="number">
                </div>
                @error('quantity')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="date">Date</label>
                <div class="@error('date')  border-red-400  @enderror border-2 p-1">
                    <input value="{{$productOutcome->date}}" type="date" name="date" class="text-sm text-black w-full h-full focus:outline-none" id="date" type="text">
                </div>
                 @error('date')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <button class="bg-gray-600 text-white w-full p-2 rounded text-sm">Update Data</button>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('js/supplies/update.js') }}"></script>
@endsection

