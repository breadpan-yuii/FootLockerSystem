@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
    <form method="GET" class="flex flex-wrap gap-2 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or customer"
               class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
        <select name="status" class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
            <option value="">All statuses</option>
            @foreach (\App\Models\Order::STATUSES as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="bg-gray-900 text-white text-sm font-semibold px-4 rounded-md">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold px-4 py-2.5 rounded-md border border-gray-300">Reset</a>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100 bg-gray-50">
                    <th class="px-5 py-3 font-semibold">Order #</th>
                    <th class="px-5 py-3 font-semibold">Customer</th>
                    <th class="px-5 py-3 font-semibold">Date</th>
                    <th class="px-5 py-3 font-semibold">Items</th>
                    <th class="px-5 py-3 font-semibold">Total</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $order->order_number }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->full_name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->order_date->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->items_count }}</td>
                        <td class="px-5 py-3 font-semibold">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span></td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-semibold text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
    </div>
@endsection
