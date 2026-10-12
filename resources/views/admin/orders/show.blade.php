@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-red-600">&larr; Back to orders</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">Order Items</h2>
                    <span class="text-xs font-bold uppercase px-3 py-1.5 rounded-full {{ $order->status_badge }}">{{ $order->status }}</span>
                </div>
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex gap-4 border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                            <img src="{{ $item->product->image_url }}" class="w-16 h-16 rounded object-cover bg-gray-100" alt="">
                            <div class="flex-1">
                                <p class="text-xs font-semibold uppercase text-gray-500">{{ $item->product->brand->name ?? '' }}</p>
                                <p class="font-semibold">{{ $item->product->name }}</p>
                                @if ($item->variant)
                                    <p class="text-sm text-gray-500">Size: {{ $item->variant->size }}@if($item->variant->color) · {{ $item->variant->color }}@endif</p>
                                @endif
                                <p class="text-sm text-gray-500">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                            </div>
                            <p class="font-bold">₱{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 mt-4 pt-4 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>₱{{ number_format($order->subtotal, 2) }}</span></div>
                    <div class="flex justify-between text-gray-600"><span>Shipping</span><span>{{ $order->shipping_fee > 0 ? '₱'.number_format($order->shipping_fee, 2) : 'Free' }}</span></div>
                </div>
                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-800 mb-4">Update Status</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-wrap gap-3 items-end">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold mb-1">Order Status</label>
                        <select name="status" class="rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                            @foreach (\App\Models\Order::STATUSES as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide px-6 py-2.5 rounded-md text-sm">Save</button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-sm">
                <h2 class="font-bold text-gray-800 mb-3">Customer</h2>
                <p class="font-semibold">{{ $order->full_name }}</p>
                <p class="text-gray-600">{{ $order->phone }}</p>
                <p class="text-gray-600 mt-1">{{ $order->address }}</p>
                @if ($order->user)
                    <a href="{{ route('admin.customers.show', $order->user) }}" class="text-blue-600 font-semibold hover:underline mt-2 inline-block">View customer</a>
                @endif
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-sm">
                <h2 class="font-bold text-gray-800 mb-3">Details</h2>
                <p class="text-gray-600"><span class="font-semibold text-gray-800">Order #:</span> {{ $order->order_number }}</p>
                <p class="text-gray-600 mt-1"><span class="font-semibold text-gray-800">Placed:</span> {{ $order->order_date->format('M d, Y g:i A') }}</p>
                <p class="text-gray-600 mt-1"><span class="font-semibold text-gray-800">Payment:</span> {{ $order->payment_method }}</p>
                @if ($order->notes)
                    <p class="text-gray-600 mt-1"><span class="font-semibold text-gray-800">Notes:</span> {{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
