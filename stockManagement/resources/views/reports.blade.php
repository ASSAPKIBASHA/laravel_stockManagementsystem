@extends('layouts.app')

@section('page_title', 'Reports')

@section('content')
<div class="grid grid-cols-1 gap-8 mt-8">
    <!-- Report Filter Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Filter Report</h2>
        <form action="/reports" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block mb-1 text-gray-700">Start Date</label>
                    <input type="date" name="start_date" class="input-style" value="{{ $startDate ?? '' }}" required>
                </div>
                <div>
                    <label class="block mb-1 text-gray-700">End Date</label>
                    <input type="date" name="end_date" class="input-style" value="{{ $endDate ?? '' }}" required>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn-style w-full">Show Report</button>
                </div>
            </div>
        </form>
    </div>

    @if(isset($startDate) && isset($endDate) && isset($summary))
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-900">Total Stock In</h3>
            <p class="text-3xl font-bold text-green-600">{{ $summary['total_stock_in'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-900">Total Stock Out</h3>
            <p class="text-3xl font-bold text-red-600">{{ $summary['total_stock_out'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2 text-gray-900">Products Involved</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $summary['product_count'] }}</p>
        </div>
    </div>
    @endif

    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900">
            Report
            @if(isset($startDate) && isset($endDate))
                <span class="text-sm font-normal text-gray-600">
                    ({{ $startDate }} to {{ $endDate }})
                </span>
            @endif
        </h2>

        @if(isset($startDate) && isset($endDate))
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700">Product Code</th>
                            <th class="px-4 py-2 text-left text-gray-700">Product Name</th>
                            <th class="px-4 py-2 text-left text-gray-700">Type</th>
                            <th class="px-4 py-2 text-left text-gray-700">Quantity</th>
                            <th class="px-4 py-2 text-left text-gray-700">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($stockActivities) > 0)
                            @foreach($stockActivities as $activity)
                                <tr>
                                    <td class="px-4 py-2">{{ $activity['product_code'] }}</td>
                                    <td class="px-4 py-2">{{ $activity['product_name'] }}</td>
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
                        @else
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">No records found for the selected date range</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">Please select a date range to view the report.</span>
            </div>
        @endif
    </div>
</div>
@endsection