@extends('layouts.admin')

@section('title', 'Customer')

@section('content')
    <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-500 hover:text-red-600">&larr; Back to customers</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="w-16 h-16 rounded-full bg-red-600 text-white flex items-center justify-center text-2xl font-bold mb-4">{{ strtoupper(substr($customer->name, 0, 1)) }}</div>
            <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
            <dl class="mt-4 space-y-3 text-sm">
                <div><dt class="text-gray-500">Email</dt><dd class="font-medium">{{ $customer->email }}</dd></div>
                <div><dt class="text-gray-500">Phone</dt><dd class="font-medium">{{ $customer->phone ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Address</dt><dd class="font-medium">{{ $customer->address ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Joined</dt><dd class="font-medium">{{ $customer->created_at->format('M d, Y') }}</dd></div>
            </dl>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Order History ({{ $customer->orders->count() }})</h2>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3 font-semibold">Order #</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 font-semibold">Total</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->orders as $order)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-gray-800 hover:text-red-600">{{ $order->order_number }}</a></td>
                            <td class="px-5 py-3 text-gray-600">{{ $order->order_date->format('M d, Y') }}</td>
                            <td class="px-5 py-3 font-semibold">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-5 py-3"><span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
