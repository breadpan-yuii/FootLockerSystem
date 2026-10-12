@extends('layouts.storefront')

@section('title', 'Track Order')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-2">Track Your Order</h1>
        <p class="text-gray-500 mb-8">Enter your order number (e.g. FL-XXXXXXXX) to check its status.</p>

        <form method="POST" action="{{ route('orders.track.search') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input type="text" name="order_number" value="{{ old('order_number', $orderNumber ?? '') }}" placeholder="FL-XXXXXXXX" required
                   class="flex-1 rounded-full border-gray-300 focus:border-red-500 focus:ring-red-500">
            <button class="bg-black hover:bg-gray-800 text-white font-bold uppercase tracking-wide px-8 py-3 rounded-full text-sm">Track</button>
        </form>
        @error('order_number') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror

        @isset($searched)
            <div class="mt-8">
                @if ($order)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div>
                                <p class="font-bold text-lg">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->order_date->format('M d, Y g:i A') }}</p>
                            </div>
                            <span class="text-sm font-bold uppercase tracking-wide px-4 py-2 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">{{ $order->items->sum('quantity') }} items · Total ₱{{ number_format($order->total_amount, 2) }}</p>

                        <div class="space-y-3">
                            @foreach (\App\Models\Order::STATUSES as $status)
                                @continue($status === 'cancelled')
                                @php($reached = array_search($status, \App\Models\Order::STATUSES, true) <= array_search($order->status, \App\Models\Order::STATUSES, true))
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full {{ $reached ? 'bg-red-600' : 'bg-gray-300' }}"></span>
                                    <span class="text-sm font-semibold uppercase tracking-wide {{ $reached ? 'text-gray-900' : 'text-gray-400' }}">{{ $status }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm">
                        No order found with number "{{ $orderNumber }}". Please check and try again.
                    </div>
                @endif
            </div>
        @endisset
    </div>
@endsection
