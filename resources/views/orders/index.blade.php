@extends('layouts.storefront')

@section('title', 'My Orders')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-6">My Orders</h1>

        @if ($orders->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-lg">
                <p class="text-gray-500">You haven't placed any orders yet.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block bg-black text-white font-bold uppercase tracking-wide px-8 py-3 rounded-full text-sm hover:bg-gray-800">Start Shopping</a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="block border border-gray-200 rounded-lg p-5 hover:border-red-400 transition">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="font-bold text-gray-900">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->order_date->format('M d, Y g:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold">₱{{ number_format($order->total_amount, 2) }}</p>
                                <p class="text-sm text-gray-500">{{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}</p>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-wide px-3 py-1.5 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
