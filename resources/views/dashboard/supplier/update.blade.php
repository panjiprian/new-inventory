@extends('layouts.main')

@section('container')
<div class="container px-4">
    <div class="bg-white p-5 mt-5 rounded-lg">
        <div class="flex">
            <h2 class="text-gray-600 font-bold">Update Supplier</h2>
        </div>

        <form action="/ubah-supplier/{{$supplier->id}}" method="POST" class="w-1/2 mt-5">
            @csrf
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="name">Supplier Name</label>
                <div class="border-2 p-1 @error('name')  border-red-400  @enderror">
                    <input name="name" value="{{$supplier->name}}" class="w-full h-full focus:outline-none text-sm" id="name" type="text">
                </div>
                @error('name')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="address">Address</label>
                <div class="@error('address')  border-red-400  @enderror border-2 p-1">
                    <input value="{{$supplier->address}}"  name="address" class="text-sm w-full h-full focus:outline-none" id="address" type="text">
                </div>
                @error('address')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
            <div class="mt-3">
                <label class="text-sm text-gray-600" for="email">Email</label>
                <div class="@error('email')  border-red-400  @enderror border-2 p-1">
                    <input type="email" value="{{$supplier->email}}"  name="email" class="text-sm w-full h-full focus:outline-none" id="email" type="text">
                </div>
                 @error('email')
                    <p class="italic text-red-500 text-sm mt-1">{{$message}}</p>
                @enderror
            </div>
             <div class="mt-3">
                <label class="text-sm text-gray-600" for="phone">Phone Number</label>
                <div class="@error('phone')  border-red-400  @enderror border-2 p-1">
                    <input value="{{$supplier->phone}}"  name="phone" class="text-sm w-full h-full focus:outline-none" id="phone" type="text">
                </div>
                 @error('phone')
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

