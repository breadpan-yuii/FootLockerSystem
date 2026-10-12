@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['label' => 'Total Sales', 'value' => '₱'.number_format($stats['revenue'], 2), 'color' => 'bg-red-600'],
                ['label' => 'Total Orders', 'value' => number_format($stats['orders']), 'color' => 'bg-gray-900'],
                ['label' => 'Pending Orders', 'value' => number_format($stats['pending']), 'color' => 'bg-yellow-500'],
                ['label' => 'Products', 'value' => number_format($stats['products']), 'color' => 'bg-gray-700'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ $card['label'] }}</p>
                    <span class="w-2.5 h-2.5 rounded-full {{ $card['color'] }}"></span>
                </div>
                <p class="text-2xl font-bold text-gray-900 mt-3">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-red-600 hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-100">
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Customer</th>
                            <th class="px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-gray-800 hover:text-red-600">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-5 py-3 text-gray-600">{{ $order->full_name }}</td>
                                <td class="px-5 py-3 font-semibold">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-5 py-3"><span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="font-bold text-gray-800 mb-4">Orders by Status</h2>
                <div class="space-y-3">
                    @foreach ($statusCounts as $status => $count)
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-gray-600 uppercase tracking-wide">{{ $status }}</span>
                            <span class="font-bold text-gray-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="font-bold text-gray-800 mb-4">Top Products</h2>
                <div class="space-y-3">
                    @forelse ($topProducts as $product)
                        <div class="flex items-center justify-between text-sm gap-3">
                            <span class="text-gray-700 line-clamp-1">{{ $product->name }}</span>
                            <span class="font-bold text-gray-900 whitespace-nowrap">{{ $product->order_items_count }} sold</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No sales yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
