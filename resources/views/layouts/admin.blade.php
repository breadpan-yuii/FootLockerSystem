<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/favicon.png') }}">
        <title>@yield('title', 'Admin') | Foot Locker Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&family=anton&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 text-gray-900">
    <div class="flex min-h-screen">
        <aside class="hidden lg:flex lg:flex-col w-64 bg-gray-950 text-gray-300 shrink-0 shadow-[inset_-4px_0_0_#dc2626]">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-5 border-b border-gray-800">
                <img src="{{ asset('images/logo-mark-inverse.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto">
                <span class="block text-[10px] font-semibold uppercase tracking-widest text-gray-500 mt-2">Admin Panel</span>
            </a>
            <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                @php
                    $links = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'admin.products.index', 'label' => 'Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'admin.categories.index', 'label' => 'Categories', 'icon' => 'M4 6h16M4 12h16M4 18h7'],
                        ['route' => 'admin.brands.index', 'label' => 'Brands', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                        ['route' => 'admin.orders.index', 'label' => 'Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'admin.customers.index', 'label' => 'Customers', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a3 3 0 11-3-3 3 3 0 013 3z'],
                    ];
                @endphp
                @foreach ($links as $link)
                    @php($active = request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route'])))
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $active ? 'bg-red-600 text-white font-semibold' : 'hover:bg-gray-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $link['icon'] }}"/></svg>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
            <div class="px-3 py-4 border-t border-gray-800 text-sm space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('View Store') }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 min-w-0">
            <header class="bg-white border-b-2 border-gray-900 border-t-4 border-t-red-600 sticky top-0 z-30">
                <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="lg:hidden">
                            <img src="{{ asset('images/logo-mark.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
                        </a>
                        <h1 class="text-lg font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 hidden sm:block">{{ auth()->user()->name }}</span>
                        <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    </div>
                </div>
                <div class="lg:hidden flex gap-2 overflow-x-auto px-4 pb-3 text-sm">
                    @foreach ($links as $link)
                        <a href="{{ route($link['route']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-full {{ request()->routeIs($link['route']) ? 'bg-red-600 text-white' : 'bg-gray-100' }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </header>

            <main class="p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
