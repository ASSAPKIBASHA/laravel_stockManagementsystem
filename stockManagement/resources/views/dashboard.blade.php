@extends('layouts.app')

@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <span class="text-gray-900 text-lg font-semibold mb-2">Total Products</span>
        <span class="text-3xl font-bold text-blue-600">{{ $totalProducts }}</span>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <span class="text-gray-900 text-lg font-semibold mb-2">Total Stock In</span>
        <span class="text-3xl font-bold text-green-600">{{ $totalStockIn }}</span>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <span class="text-gray-900 text-lg font-semibold mb-2">Total Stock Out</span>
        <span class="text-3xl font-bold text-red-600">{{ $totalStockOut }}</span>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <span class="text-gray-900 text-lg font-semibold mb-2">Current Stock</span>
        <span class="text-3xl font-bold text-purple-600">{{ $currentStock }}</span>
    </div>
</div>

<!-- Recent Activities and Low Stock Products -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
    <!-- Recent Activities -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Recent Activities</h2>
        @if(count($recentActivities) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left text-gray-700">Type</th>
                            <th class="px-4 py-2 text-left text-gray-700">Qty</th>
                            <th class="px-4 py-2 text-left text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                            <tr>
                                <td class="px-4 py-2">{{ $activity['product_code'] }}</td>
                                <td class="px-4 py-2">
                                    @if($activity['type'] == 'IN')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">IN</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">OUT</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $activity['quantity'] }}</td>
                                <td class="px-4 py-2">{{ $activity['date'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center">No recent activities found</p>
        @endif
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Low Stock Products</h2>
        @if(count($lowStockProducts) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700">Product</th>
                            <th class="px-4 py-2 text-left text-gray-700">Code</th>
                            <th class="px-4 py-2 text-left text-gray-700">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                            <tr>
                                <td class="px-4 py-2">{{ $product->product_name }}</td>
                                <td class="px-4 py-2">{{ $product->product_code }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                        {{ $product->current_stock }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center">No low stock products found</p>
        @endif
    </div>
</div>
@endsection