@extends('layouts.app')

@section('page_title', 'Stock In')

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

    <!-- Stock In Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Add Stock In</h2>
        <form action="/stockin" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 text-gray-700">Product</label>
                <select name="product_id" class="input-style" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_code }} - {{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1 text-gray-700">Quantity</label>
                <input type="number" name="quantity" class="input-style" min="1" value="{{ old('quantity') }}" required>
            </div>
            <button type="submit" class="btn-style">Add Stock In</button>
        </form>
    </div>
    <!-- Stock In Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Stock In List</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-gray-700">Product Code</th>
                    <th class="px-4 py-2 text-left text-gray-700">Product Name</th>
                    <th class="px-4 py-2 text-left text-gray-700">Quantity</th>
                    <th class="px-4 py-2 text-left text-gray-700">Date</th>
                </tr>
            </thead>
            <tbody>
                @if(count($stockIns) > 0)
                    @foreach($stockIns as $stockIn)
                    <tr>
                        <td class="px-4 py-2">{{ $stockIn->product->product_code }}</td>
                        <td class="px-4 py-2">{{ $stockIn->product->product_name }}</td>
                        <td class="px-4 py-2">{{ $stockIn->quantity }}</td>
                        <td class="px-4 py-2">{{ $stockIn->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">No stock in records found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection