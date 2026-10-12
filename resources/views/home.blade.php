@extends('layouts.storefront')

@section('title', 'Home')

@section('content')
    <section class="relative bg-gray-950 text-white overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-red-600 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 w-72 h-72 bg-red-600 rounded-full opacity-10 blur-3xl"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 items-center gap-12">
            <div>
                <span class="inline-block bg-red-600 text-white text-xs font-bold tracking-widest uppercase px-3 py-1.5 shadow-[3px_3px_0_#ffffff33]">New Season Drops</span>
                <h1 class="mt-5 font-display uppercase leading-none text-5xl sm:text-6xl lg:text-7xl">
                    Step into<br><span class="text-red-600">the latest.</span>
                </h1>
                <p class="mt-5 max-w-lg text-gray-300">Shop the freshest sneakers, apparel and accessories from the brands you love — delivered nationwide.</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('shop') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide px-8 py-3 rounded-sm shadow-[4px_4px_0_#000] transition">Shop Now</a>
                    <a href="{{ route('shop', ['sort' => 'price_low']) }}" class="bg-white hover:bg-gray-100 text-gray-900 font-bold uppercase tracking-wide px-8 py-3 rounded-sm shadow-[4px_4px_0_#000] transition">Best Deals</a>
                </div>
                <div class="mt-8 flex items-center gap-6 text-sm text-gray-400">
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Free shipping ₱3,000+</span>
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>COD available</span>
                    <span class="flex items-center gap-2"><svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Easy returns</span>
                </div>
            </div>

            <div class="relative">
                <span class="absolute top-3 -right-2 z-30 bg-red-600 text-white font-display uppercase tracking-widest text-sm px-4 py-1.5 rotate-6 shadow-[3px_3px_0_#000]">New drops</span>
                <div class="grid grid-cols-2 gap-4 sm:gap-6">
                    <img src="{{ asset('images/products/nike-air-force-1-07.jpg') }}" alt="Nike Air Force 1 '07" class="w-full rounded-xl border border-white/10 shadow-2xl -rotate-3 hover:rotate-0 transition transform duration-300">
                    <img src="{{ asset('images/products/adidas-superstar.jpg') }}" alt="adidas Superstar" class="w-full rounded-xl border border-white/10 shadow-2xl rotate-3 mt-8 hover:rotate-0 transition transform duration-300">
                    <img src="{{ asset('images/products/vans-old-skool.jpg') }}" alt="Vans Old Skool" class="w-full rounded-xl border border-white/10 shadow-2xl rotate-2 -mb-4 hover:rotate-0 transition transform duration-300">
                    <img src="{{ asset('images/products/puma-rs-x.png') }}" alt="Puma RS-X" class="w-full rounded-xl border border-white/10 shadow-2xl -rotate-2 -mt-4 hover:rotate-0 transition transform duration-300">
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-r from-red-700 via-red-600 to-red-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-center gap-4">
            <span class="font-display uppercase tracking-widest text-lg sm:text-xl">Free Shipping on orders over ₱3,000</span>
            <span class="hidden sm:inline-block text-white bg-black/20 border-2 border-white px-4 py-1 rounded-sm font-bold uppercase text-xs tracking-widest">Today only</span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
    </section>

    @php
        $tileImages = [
            'mens-shoes' => 'nike-air-force-1-07.jpg',
            'womens-shoes' => 'adidas-superstar.jpg',
            'kids-shoes' => 'nike-air-max-sc-kids.png',
            'apparel' => 'nike-sportswear-club-fleece-hoodie.png',
            'accessories' => 'new-balance-performance-cap.jpg',
        ];
    @endphp
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($categories as $category)
                <a href="{{ route('category.show', $category) }}" class="relative rounded-lg overflow-hidden aspect-[4/3] group bg-gray-900 border-2 border-gray-900 shadow-[4px_4px_0_#000]">
                    @if (isset($tileImages[$category->slug]))
                        <img src="{{ asset('images/products/'.$tileImages[$category->slug]) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300 opacity-90">
                    @endif
                    <span class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex items-end justify-between p-3">
                        <span class="text-white font-display uppercase tracking-wide text-sm sm:text-base">
                            {{ $category->name }}
                        </span>
                        <svg class="w-5 h-5 text-red-500 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="font-display text-3xl text-red-600">01</span>
            <h2 class="font-display uppercase tracking-wide text-2xl sm:text-3xl">Featured</h2>
            <span class="flex-1 h-1 bg-gray-900"></span>
            <a href="{{ route('shop') }}" class="text-sm font-semibold text-red-600 hover:underline">View all</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8">
            @forelse ($featured as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="text-gray-500 col-span-full">No featured products yet. Add some from the admin panel.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-gray-100 border-y-2 border-gray-900 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="flex items-center gap-3 mb-6">
                <span class="font-display text-3xl text-red-600">02</span>
                <h2 class="font-display uppercase tracking-wide text-2xl sm:text-3xl">Shop by Brand</h2>
                <span class="flex-1 h-1 bg-gray-900"></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach ($brands as $brand)
                    <a href="{{ route('brand.show', $brand) }}"
                       class="group flex items-center gap-3 rounded-lg bg-white border-2 border-gray-900 p-4 shadow-[4px_4px_0_#000] hover:shadow-[2px_2px_0_#000] hover:translate-x-0.5 hover:translate-y-0.5 transition">
                        <span class="w-11 h-11 rounded-sm bg-red-600 text-white font-display text-xl flex items-center justify-center shrink-0">{{ strtoupper(substr($brand->name, 0, 1)) }}</span>
                        <span class="font-display uppercase tracking-wide text-lg leading-none text-gray-900 group-hover:text-red-600 transition">{{ $brand->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center gap-3 mb-6">
            <span class="font-display text-3xl text-red-600">03</span>
            <h2 class="font-display uppercase tracking-wide text-2xl sm:text-3xl">New Arrivals</h2>
            <span class="flex-1 h-1 bg-gray-900"></span>
            <a href="{{ route('shop') }}" class="text-sm font-semibold text-red-600 hover:underline">View all</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8">
            @forelse ($newArrivals as $product)
                <x-product-card :product="$product" />
            @empty
                <p class="text-gray-500 col-span-full">No products yet. Add some from the admin panel.</p>
            @endforelse
        </div>
    </section>
@endsection