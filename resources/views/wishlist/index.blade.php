@extends('layouts.storefront')

@section('title', 'My Wishlist')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-black italic tracking-tighter uppercase mb-6">My Wishlist</h1>

        @if ($items->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-lg">
                <p class="text-gray-500">Your wishlist is empty.</p>
                <a href="{{ route('shop') }}" class="mt-4 inline-block bg-black text-white font-bold uppercase tracking-wide px-8 py-3 rounded-full text-sm hover:bg-gray-800">Browse Products</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8">
                @foreach ($items as $item)
                    @if ($item->product)
                        <div>
                            <x-product-card :product="$item->product" />
                            <form method="POST" action="{{ route('wishlist.toggle', $item->product) }}" class="mt-2">
                                @csrf
                                <button class="text-sm text-gray-500 hover:text-red-600 font-semibold">Remove</button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection
