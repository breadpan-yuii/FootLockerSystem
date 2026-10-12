@extends('layouts.storefront')

@section('title', 'Order '.$order->order_number)

@section('content')
    @php($steps = ['pending', 'confirmed', 'preparing', 'completed'])
    @php($currentIndex = array_search($order->status, $steps, true))
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('orders.index') }}" class="text-sm text-gray-500 hover:text-red-600">&larr; Back to my orders</a>

        <div class="flex flex-wrap items-center justify-between gap-4 mt-4 mb-6">
            <div>
                <h1 class="text-2xl font-black tracking-tight">Order {{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500">Placed on {{ $order->order_date->format('M d, Y g:i A') }}</p>
            </div>
            <span class="text-sm font-bold uppercase tracking-wide px-4 py-2 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span>
        </div>

        @if ($order->status === 'cancelled')
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">This order was cancelled.</div>
        @else
            <div class="mb-8">
                <div class="flex items-center">
                    @foreach ($steps as $index => $step)
                        <div class="flex-1 flex items-center {{ $index < count($steps) - 1 ? '' : 'flex-none' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
                                            {{ $index <= $currentIndex ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                    {{ $index + 1 }}
                                </div>
                                <span class="mt-2 text-xs font-semibold uppercase tracking-wide {{ $index <= $currentIndex ? 'text-red-600' : 'text-gray-400' }}">{{ $step }}</span>
                            </div>
                            @if ($index < count($steps) - 1)
                                <div class="flex-1 h-1 mx-1 rounded {{ $index < $currentIndex ? 'bg-red-600' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                <h2 class="font-bold uppercase tracking-wide">Items</h2>
                @foreach ($order->items as $item)
                    <div class="flex gap-4 border border-gray-200 rounded-lg p-4">
                        <div class="w-20 h-20 shrink-0 rounded-md overflow-hidden bg-gray-100">
                            <img src="{{ $item->product->image_url }}" class="w-full h-full object-cover" alt="">
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                            <p class="font-semibold">{{ $item->product->name }}</p>
                            @if ($item->variant)
                                <p class="text-sm text-gray-500">Size: {{ $item->variant->size }}</p>
                            @endif
                            <p class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                        </div>
                        <div class="font-bold whitespace-nowrap">₱{{ number_format($item->subtotal, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <aside class="space-y-6">
                <div class="border border-gray-200 rounded-lg p-5">
                    <h2 class="font-bold uppercase tracking-wide mb-3">Summary</h2>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-600">Subtotal</span><span>₱{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between text-sm mb-1"><span class="text-gray-600">Shipping</span><span>{{ $order->shipping_fee > 0 ? '₱'.number_format($order->shipping_fee, 2) : 'Free' }}</span></div>
                    <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold"><span>Total</span><span>₱{{ number_format($order->total_amount, 2) }}</span></div>
                </div>
                <div class="border border-gray-200 rounded-lg p-5 text-sm">
                    <h2 class="font-bold uppercase tracking-wide mb-3">Delivery</h2>
                    <p class="font-semibold">{{ $order->full_name }}</p>
                    <p class="text-gray-600">{{ $order->phone }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                    <p class="mt-3 text-gray-600"><span class="font-semibold">Payment:</span> {{ $order->payment_method }}</p>
                    @if ($order->notes)
                        <p class="mt-3 text-gray-600"><span class="font-semibold">Notes:</span> {{ $order->notes }}</p>
                    @endif
                </div>
            </aside>
        </div>
    </div>
@endsection
