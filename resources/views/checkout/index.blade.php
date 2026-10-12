@extends('layouts.storefront')

@section('title', 'Checkout')

@section('content')
    @php($shipping = $cart->total >= 3000 ? 0 : 150)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-6">Checkout</h1>

        <form method="POST" action="{{ route('checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @csrf
            <div class="lg:col-span-2 space-y-6">
                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold uppercase tracking-wide mb-4">Delivery Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold mb-1">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                                   class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                            @error('full_name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-semibold mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                   class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                            @error('phone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold mb-1">Delivery Address</label>
                            <textarea name="address" rows="3" required
                                      class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold mb-1">Order Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea name="notes" rows="2" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg p-6">
                    <h2 class="text-lg font-bold uppercase tracking-wide mb-4">Payment Method</h2>
                    @foreach (['Cash on Delivery', 'GCash', 'Bank Transfer'] as $method)
                        <label class="flex items-center gap-3 py-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="{{ $method }}" @checked(old('payment_method', 'Cash on Delivery') === $method)
                                   class="text-red-600 focus:ring-red-500">
                            <span class="font-medium">{{ $method }}</span>
                        </label>
                    @endforeach
                    <p class="text-sm text-gray-500 mt-2">Payment is collected manually upon confirmation of your order.</p>
                </div>
            </div>

            <aside class="lg:col-span-1">
                <div class="border border-gray-200 rounded-lg p-6 sticky top-28">
                    <h2 class="text-lg font-bold uppercase tracking-wide mb-4">Your Order</h2>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach ($cart->items as $item)
                            <div class="flex gap-3">
                                <img src="{{ $item->product->image_url }}" class="w-12 h-12 rounded object-cover bg-gray-100" alt="">
                                <div class="flex-1 min-w-0 text-sm">
                                    <p class="font-medium line-clamp-1">{{ $item->product->name }}</p>
                                    <p class="text-gray-500">Qty: {{ $item->quantity }}@if ($item->variant) · {{ $item->variant->size }}@endif</p>
                                </div>
                                <span class="text-sm font-semibold">₱{{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 mt-4 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-600">Subtotal</span><span class="font-semibold">₱{{ number_format($cart->total, 2) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Shipping</span><span class="font-semibold text-green-600">{{ $shipping === 0 ? 'Free' : '₱'.number_format($shipping, 2) }}</span></div>
                        <div class="border-t border-gray-200 pt-3 flex justify-between text-lg font-bold"><span>Total</span><span>₱{{ number_format($cart->total + $shipping, 2) }}</span></div>
                    </div>
                    <button type="submit" class="mt-5 w-full bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide py-3.5 rounded-full text-sm">
                        Place Order
                    </button>
                </div>
            </aside>
        </form>
    </div>
@endsection
