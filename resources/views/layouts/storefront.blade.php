<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/favicon.png') }}">
        <title>@yield('title', config('app.name')) | Sneakers, Apparel & More</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&family=anton&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-900 flex flex-col min-h-screen">
    <div class="bg-red-600 text-white text-center text-xs tracking-wide py-2">
        Free shipping on orders over ₱3,000 &nbsp;•&nbsp; Cash on Delivery available nationwide
    </div>

    <header class="sticky top-0 z-40 bg-white border-b-2 border-gray-900 shadow-[0_2px_0_#000]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20 gap-4">
                <a href="{{ route('home') }}" class="shrink-0">
                    <img src="{{ asset('images/logo-mark.png') }}" alt="{{ config('app.name') }}" class="h-9 lg:h-11 w-auto">
                </a>

                <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold">
                    <a href="{{ route('shop') }}" class="relative text-gray-900 uppercase tracking-widest hover:text-red-600 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:bg-red-600 after:w-0 hover:after:w-full after:transition-all after:duration-200">{{ __('All Shoes') }}</a>
                    @foreach ($navCategories->take(3) as $category)
                        <a href="{{ route('category.show', $category) }}" class="relative text-gray-900 uppercase tracking-widest hover:text-red-600 after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:bg-red-600 after:w-0 hover:after:w-full after:transition-all after:duration-200">{{ $category->name }}</a>
                    @endforeach
                    <div class="relative group">
                        <button class="relative text-gray-900 uppercase tracking-widest inline-flex items-center gap-1 hover:text-red-600">
                            {{ __('Brands') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute left-0 top-full pt-2 w-48 hidden group-hover:block">
                            <div class="bg-white border-2 border-gray-900 shadow-[3px_3px_0_#000] rounded-sm py-2">
                                @foreach ($navBrands as $brand)
                                    <a href="{{ route('brand.show', $brand) }}" class="block px-4 py-1.5 text-sm hover:bg-red-50 font-semibold">{{ $brand->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="flex items-center gap-3 lg:gap-4">
                    <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search"
                                   class="w-40 lg:w-56 rounded-md border-2 border-gray-900 text-sm pl-9 focus:border-red-600 focus:ring-red-500 shadow-[2px_2px_0_#000]">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </form>

                    <a href="{{ route('wishlist.index') }}" class="relative p-1 hover:text-red-600" title="Wishlist">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </a>

                    <div class="relative group">
                        <button class="p-1 hover:text-red-600" title="Account">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>
                        <div class="absolute right-0 top-full pt-2 w-52 hidden group-hover:block">
                            <div class="bg-white border-2 border-gray-900 shadow-[3px_3px_0_#000] rounded-sm py-2 text-sm">
                                @auth
                                    <div class="px-4 py-1.5 font-semibold text-gray-500">{{ auth()->user()->name }}</div>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-1.5 hover:bg-red-50">{{ __('My Orders') }}</a>
                                    <a href="{{ route('wishlist.index') }}" class="block px-4 py-1.5 hover:bg-red-50">{{ __('My Wishlist') }}</a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-1.5 hover:bg-red-50 text-red-600 font-semibold">{{ __('Admin Panel') }}</a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-1.5 hover:bg-red-50">{{ __('Profile') }}</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="block w-full text-left px-4 py-1.5 hover:bg-red-50">{{ __('Log Out') }}</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="block px-4 py-1.5 hover:bg-red-50 font-bold">{{ __('Login') }}</a>
                                    <a href="{{ route('register') }}" class="block px-4 py-1.5 hover:bg-red-50">{{ __('Register') }}</a>
                                @endauth
                                <a href="{{ route('orders.track') }}" class="block px-4 py-1.5 hover:bg-red-50 border-t-2 border-gray-900 mt-1 pt-2">{{ __('Track Order') }}</a>
                            </div>
                        </div>
                    </div>

                    @php($cartCount = auth()->check() ? (auth()->user()->cart?->items->sum('quantity') ?? 0) : 0)
                    <a href="{{ route('cart.index') }}" class="relative p-1 hover:text-red-600" title="Bag">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @if ($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="lg:hidden pb-3">
                <div class="flex gap-4 overflow-x-auto text-sm font-semibold uppercase tracking-widest">
                    <a href="{{ route('shop') }}" class="whitespace-nowrap hover:text-red-600">{{ __('All Shoes') }}</a>
                    @foreach ($navCategories as $category)
                        <a href="{{ route('category.show', $category) }}" class="whitespace-nowrap hover:text-red-600">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="bg-green-50 border-y border-green-200 text-green-800 text-sm">
            <div class="max-w-7xl mx-auto px-4 py-3">{{ session('success') }}</div>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-y border-red-200 text-red-800 text-sm">
            <div class="max-w-7xl mx-auto px-4 py-3">{{ session('error') }}</div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-gray-950 text-gray-300 mt-16 shadow-[inset_0_6px_0_#dc2626]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <img src="{{ asset('images/logo-mark-inverse.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto mb-3">
                <p class="text-sm text-gray-400">Your home for sneakers, apparel and more. Educational project — not affiliated with Foot Locker Inc.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold uppercase text-sm mb-3">{{ __('Shop') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('shop') }}" class="hover:text-white">{{ __('All Shoes') }}</a></li>
                    @foreach ($navCategories as $category)
                        <li><a href="{{ route('category.show', $category) }}" class="hover:text-white">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold uppercase text-sm mb-3">{{ __('Brands') }}</h4>
                <ul class="space-y-2 text-sm">
                    @foreach ($navBrands->take(6) as $brand)
                        <li><a href="{{ route('brand.show', $brand) }}" class="hover:text-white">{{ $brand->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold uppercase text-sm mb-3">{{ __('Customer Service') }}</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('orders.track') }}" class="hover:text-white">{{ __('Track Order') }}</a></li>
                    <li><a href="{{ route('orders.index') }}" class="hover:text-white">{{ __('My Orders') }}</a></li>
                    <li><a href="{{ route('wishlist.index') }}" class="hover:text-white">{{ __('Wishlist') }}</a></li>
                    <li class="text-gray-400">Mon–Sun, 9am–8pm PHT</li>
                    <li class="text-gray-400">connect@footlocker.test</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 py-4 text-center text-xs text-gray-500">
            {{ date('Y') }} Foot Locker PH — Student Project. All rights reserved.
        </div>
    </footer>
</body>
</html>
