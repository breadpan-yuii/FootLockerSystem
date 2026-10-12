@extends('layouts.admin')

@section('title', $product->name)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900">{{ $product->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $product->brand->name ?? '' }} · {{ $product->category->name ?? '' }}</p>
            <p class="mt-4 text-gray-600">{{ $product->description ?: 'No description.' }}</p>

            <div class="grid grid-cols-3 gap-4 mt-6 text-sm">
                <div><p class="text-gray-500">Price</p><p class="font-bold">₱{{ number_format($product->price, 2) }}</p></div>
                <div><p class="text-gray-500">Sale Price</p><p class="font-bold">{{ $product->sale_price ? '₱'.number_format($product->sale_price, 2) : '—' }}</p></div>
                <div><p class="text-gray-500">Stock</p><p class="font-bold">{{ $product->stock }}</p></div>
            </div>

            @if ($product->variants->isNotEmpty())
                <h3 class="font-bold text-gray-800 mt-8 mb-2">Variants</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($product->variants as $variant)
                        <span class="border border-gray-200 rounded-md px-3 py-1 text-sm">{{ $variant->size }}@if($variant->color) / {{ $variant->color }}@endif — {{ $variant->stock }} pcs</span>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <img src="{{ $product->image_url }}" class="w-full rounded-lg bg-gray-100" alt="">
            <div class="flex gap-3 mt-4">
                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 text-center bg-red-600 text-white font-semibold py-2.5 rounded-md text-sm">Edit</a>
                <a href="{{ route('product.show', $product) }}" class="flex-1 text-center bg-gray-100 font-semibold py-2.5 rounded-md text-sm">View in store</a>
            </div>
        </div>
    </div>
@endsection
