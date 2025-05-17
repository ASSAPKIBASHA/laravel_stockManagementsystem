@extends('layouts.app')

@section('page_title', 'Products')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
    <!-- Success Message -->
    @if(session('success'))
    <div class="col-span-2 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="col-span-2 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Product Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Add Product</h2>
        <form action="/products" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 text-gray-700">Product Name</label>
                <input type="text" name="product_name" class="input-style" value="{{ old('product_name') }}" required>
            </div>
            <div>
                <label class="block mb-1 text-gray-700">Product Code</label>
                <input type="text" name="product_code" class="input-style" value="{{ old('product_code') }}" required>
            </div>
            <button type="submit" class="btn-style">Add Product</button>
        </form>
    </div>
    <!-- Product Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Product List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700">Name</th>
                    <th class="px-4 py-2 text-left text-gray-700">Product Code</th>
                    <th class="px-4 py-2 text-left text-gray-700">Quantity</th>
                    <th class="px-4 py-2 text-left text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($products) > 0)
                    @foreach($products as $product)
                    <tr>
                        <td class="px-4 py-2">{{ $product->product_name }}</td>
                        <td class="px-4 py-2">{{ $product->product_code }}</td>
                        <td class="px-4 py-2">{{ $product->total_quantity }}</td>
                        <td class="px-4 py-2">
                            <form action="/products/{{ $product->id }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No products found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection