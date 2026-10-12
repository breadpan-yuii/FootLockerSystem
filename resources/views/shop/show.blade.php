@extends('layouts.storefront')

@section('title', $product->name)

@section('content')
    @php
        $gallery = collect([$product->image_url])
            ->merge($product->images->map(fn ($image) => $image->image_url))
            ->filter()
            ->unique()
            ->values();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ active: '{{ $gallery->first() }}' }">
        <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-red-600">Home</a><span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-red-600">Shop</a><span>/</span>
            @if ($product->category)
                <a href="{{ route('category.show', $product->category) }}" class="hover:text-red-600">{{ $product->category->name }}</a><span>/</span>
            @endif
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div>
                <div class="aspect-square rounded-xl overflow-hidden bg-gray-100">
                    <img :src="active" src="{{ $gallery->first() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @if ($gallery->count() > 1)
                    <div class="mt-3 flex gap-3 overflow-x-auto">
                        @foreach ($gallery as $image)
                            <button type="button" @click="active = '{{ $image }}'"
                                    class="w-20 h-20 rounded-lg overflow-hidden border-2 border-transparent hover:border-red-500 shrink-0">
                                <img src="{{ $image }}" class="w-full h-full object-cover" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="text-sm font-bold uppercase tracking-widest text-red-600">{{ $product->brand->name ?? '' }}</p>
                <h1 class="text-3xl font-black tracking-tight mt-1">{{ $product->name }}</h1>

                <div class="mt-3 flex items-center gap-3">
                    @if ($product->is_on_sale)
                        <span class="text-2xl font-bold text-red-600">₱{{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-lg text-gray-400 line-through">₱{{ number_format($product->price, 2) }}</span>
                        <span class="bg-red-100 text-red-700 text-xs font-bold px-2 py-1 rounded">SALE</span>
                    @else
                        <span class="text-2xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <div class="mt-3 text-sm">
                    @if ($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 text-green-700 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span> In Stock ({{ $product->stock }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-red-600 font-semibold">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Sold Out
                        </span>
                    @endif
                </div>

                @if ($product->description)
                    <p class="mt-5 text-gray-600 leading-relaxed">{{ $product->description }}</p>
                @endif

                <form method="POST" action="{{ route('cart.store') }}" class="mt-6 space-y-5">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if ($product->variants->isNotEmpty())
                        <div>
                            <label class="block text-sm font-bold uppercase tracking-wide mb-2">Select Size</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($product->variants as $variant)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="product_variant_id" value="{{ $variant->id }}" class="peer sr-only"
                                               @disabled($variant->stock < 1) required>
                                        <span class="block min-w-[3.5rem] text-center border border-gray-300 rounded-md px-3 py-2 text-sm font-semibold
                                                     peer-checked:border-red-600 peer-checked:bg-red-600 peer-checked:text-white
                                                     peer-disabled:opacity-40 peer-disabled:cursor-not-allowed hover:border-gray-400">
                                            {{ $variant->size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('product_variant_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" max="20"
                               class="w-24 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        @error('quantity') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @auth
                            <button type="submit" @disabled($product->stock < 1)
                                    class="flex-1 min-w-[12rem] bg-black hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold uppercase tracking-wide py-3.5 rounded-full text-sm">
                                Add to Bag
                            </button>
                            <button type="submit" name="checkout" value="1"
                                    formaction="{{ route('cart.store') }}"
                                    @disabled($product->stock < 1)
                                    class="flex-1 min-w-[12rem] bg-red-600 hover:bg-red-700 disabled:opacity-50 text-white font-bold uppercase tracking-wide py-3.5 rounded-full text-sm">
                                Buy Now
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 text-center bg-black hover:bg-gray-800 text-white font-bold uppercase tracking-wide py-3.5 rounded-full text-sm">
                                Login to Purchase
                            </a>
                        @endauth
                    </div>
                </form>

                @auth
                    <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="mt-3">
                        @csrf
                        <button class="text-sm font-semibold text-gray-600 hover:text-red-600 inline-flex items-center gap-2">
                            <svg class="w-5 h-5 {{ $inWishlist ? 'text-red-600 fill-red-600' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            {{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}
                        </button>
                    </form>
                @endauth

                <dl class="mt-6 border-t border-gray-100 pt-4 text-sm text-gray-600 space-y-2">
                    <div class="flex gap-2"><dt class="font-semibold text-gray-800 w-24">Category</dt><dd>{{ $product->category->name ?? '—' }}</dd></div>
                    <div class="flex gap-2"><dt class="font-semibold text-gray-800 w-24">Gender</dt><dd>{{ ucfirst($product->gender) }}</dd></div>
                    <div class="flex gap-2"><dt class="font-semibold text-gray-800 w-24">Shipping</dt><dd>Cash on Delivery nationwide</dd></div>
                </dl>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <section class="mt-16">
                <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-6">You May Also Like</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
                    @foreach ($related as $item)
                        <x-product-card :product="$item" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
