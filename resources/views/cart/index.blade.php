@extends('layouts.storefront')

@section('title', 'Shopping Bag')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-6">Shopping Bag</h1>

        @if ($cart->items->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-lg">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <p class="mt-4 text-gray-500">Your bag is empty.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block bg-black text-white font-bold uppercase tracking-wide px-8 py-3 rounded-full text-sm hover:bg-gray-800">Start Shopping</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cart->items as $item)
                        <div class="flex gap-4 border border-gray-200 rounded-lg p-4">
                            <a href="{{ route('product.show', $item->product) }}" class="w-24 h-24 shrink-0 rounded-md overflow-hidden bg-gray-100">
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            </a>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                                <a href="{{ route('product.show', $item->product) }}" class="font-semibold text-gray-900 hover:text-red-600 line-clamp-2">{{ $item->product->name }}</a>
                                @if ($item->variant)
                                    <p class="text-sm text-gray-500 mt-0.5">Size: {{ $item->variant->size }}@if($item->variant->color) · {{ $item->variant->color }}@endif</p>
                                @endif
                                <p class="text-sm font-semibold text-gray-900 mt-1">₱{{ number_format($item->product->display_price, 2) }}</p>

                                <div class="flex items-center justify-between mt-3">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="20"
                                               onchange="this.form.submit()"
                                               class="w-16 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                                        <noscript><button class="text-xs underline">Update</button></noscript>
                                    </form>
                                    <form method="POST" action="{{ route('cart.destroy', $item) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-sm text-gray-500 hover:text-red-600 font-semibold">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right font-bold text-gray-900 whitespace-nowrap">₱{{ number_format($item->subtotal, 2) }}</div>
                        </div>
                    @endforeach
                </div>

                <aside class="lg:col-span-1">
                    <div class="border border-gray-200 rounded-lg p-6 sticky top-28">
                        <h2 class="text-lg font-bold uppercase tracking-wide mb-4">Order Summary</h2>
                        <div class="flex justify-between text-sm mb-2"><span class="text-gray-600">Subtotal ({{ $cart->count }} items)</span><span class="font-semibold">₱{{ number_format($cart->total, 2) }}</span></div>
                        <div class="flex justify-between text-sm mb-2"><span class="text-gray-600">Shipping</span><span class="font-semibold text-green-600">{{ $cart->total >= 3000 ? 'Free' : '₱150.00' }}</span></div>
                        @php($shipping = $cart->total >= 3000 ? 0 : 150)
                        <div class="border-t border-gray-200 mt-4 pt-4 flex justify-between text-lg font-bold"><span>Total</span><span>₱{{ number_format($cart->total + $shipping, 2) }}</span></div>
                        <a href="{{ route('checkout.index') }}" class="mt-5 block text-center bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide py-3.5 rounded-full text-sm">Proceed to Checkout</a>
                        <a href="{{ route('shop') }}" class="mt-3 block text-center text-sm font-semibold text-gray-600 hover:text-red-600">Continue shopping</a>
                    </div>
                </aside>
            </div>
        @endif
    </div>
@endsection
