@extends('layouts.storefront')

@section('title', $heading ?? 'Shop')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            @if (isset($activeBrand))
                <div class="relative rounded-lg overflow-hidden bg-gray-950 border-2 border-gray-900 shadow-[4px_4px_0_#000] mb-5 px-6 py-8 flex items-center gap-5">
                    <span class="w-14 h-14 rounded-sm bg-red-600 text-white font-display text-3xl flex items-center justify-center shrink-0">{{ strtoupper(substr($activeBrand->name, 0, 1)) }}</span>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-red-500 font-bold">Brand</p>
                        <h1 class="font-display uppercase tracking-wide text-3xl sm:text-4xl text-white">{{ $heading ?? 'Shop' }}</h1>
                    </div>
                    <span class="absolute right-0 top-0 h-full w-1.5 bg-red-600"></span>
                </div>
            @elseif (isset($activeCategory))
                <div class="relative rounded-lg overflow-hidden bg-gray-950 border-2 border-gray-900 shadow-[4px_4px_0_#000] mb-5 px-6 py-8 flex items-center gap-5">
                    <span class="w-1.5 h-14 bg-red-600 shrink-0"></span>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-red-500 font-bold">Category</p>
                        <h1 class="font-display uppercase tracking-wide text-3xl sm:text-4xl text-white">{{ $heading ?? 'Shop' }}</h1>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-1.5 h-8 bg-red-600"></span>
                    <h1 class="font-display uppercase tracking-wide text-3xl sm:text-4xl">{{ $heading ?? 'All Shoes' }}</h1>
                </div>
            @endif
            <p class="text-sm text-gray-500 mt-2">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} found</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                <form method="GET" action="{{ url()->current() }}" class="space-y-6">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name"
                               class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Category</label>
                        <select name="category" class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">All categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Brand</label>
                        <select name="brand" class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">All brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Gender</label>
                        <select name="gender" class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">All</option>
                            @foreach (['men', 'women', 'kids', 'unisex'] as $gender)
                                <option value="{{ $gender }}" @selected(request('gender') === $gender)>{{ ucfirst($gender) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-wide mb-2">Price Range</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" min="0"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            <span class="text-gray-400">–</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" min="0"
                                   class="w-full rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button class="flex-1 bg-red-600 text-white text-sm font-bold uppercase tracking-wide py-2.5 rounded-sm border-2 border-gray-900 shadow-[3px_3px_0_#000] hover:bg-red-700">Apply</button>
                        <a href="{{ url()->current() }}" class="px-4 py-2.5 text-sm font-semibold rounded-sm border-2 border-gray-900 hover:bg-gray-50">Reset</a>
                    </div>
                </form>
            </aside>

            <div class="lg:col-span-3">
                <div class="flex items-center justify-between mb-4">
                    <form method="GET" action="{{ url()->current() }}">
                        @foreach (request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" onchange="this.form.submit()"
                                class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">Newest</option>
                            <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                            <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
                            <option value="name" @selected(request('sort') === 'name')>Name A–Z</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="text-center py-20 bg-gray-50 rounded-lg">
                        <p class="text-gray-500">No products matched your filters.</p>
                        <a href="{{ url()->current() }}" class="mt-3 inline-block text-red-600 font-semibold hover:underline">Clear filters</a>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-8">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
