<!DOCTYPE html>
@props(['heading' => null, 'subheading' => null])
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name')) | Sneakers, Apparel & More</title>

        <link rel="icon" href="{{ asset('images/favicon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&family=anton&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col bg-gray-50">
        <div class="bg-red-600 text-white text-center text-xs tracking-wide py-2">
            Free shipping on orders over ₱3,000 &nbsp;•&nbsp; Cash on Delivery available nationwide
        </div>

        <div class="flex-1 flex flex-col justify-center items-center px-4 py-10">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo-mark.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
            </a>

            <div class="w-full sm:max-w-md mt-6 bg-white border-2 border-gray-900 shadow-[4px_4px_0_#000] rounded-lg p-6 sm:p-8">
                @if (! empty($heading))
                    <h1 class="font-display uppercase tracking-wide text-2xl mb-1">{{ $heading }}</h1>
                    @if (! empty($subheading))
                        <p class="text-sm text-gray-500 mb-5">{{ $subheading }}</p>
                    @endif
                @endif

                {{ $slot }}
            </div>

            <a href="{{ route('home') }}" class="mt-6 text-sm font-semibold text-gray-500 hover:text-red-600 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                {{ __('Continue shopping') }}
            </a>
        </div>

        <footer class="bg-gray-950 text-gray-400 text-center text-sm py-5 shadow-[inset_0_4px_0_#dc2626]">
            <a href="{{ route('home') }}" class="inline-block">
                <img src="{{ asset('images/logo-mark-inverse.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
            </a>
            <p class="mt-2 text-xs">{{ date('Y') }} Foot Locker PH — Student Project. All rights reserved.</p>
        </footer>
    </body>
</html>