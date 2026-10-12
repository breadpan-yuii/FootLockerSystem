@props(['product'])

<div class="group relative flex flex-col bg-white border-2 border-gray-900 rounded-lg shadow-[4px_4px_0_#000] hover:shadow-[2px_2px_0_#000] hover:translate-x-0.5 hover:translate-y-0.5 transition p-3">
    <a href="{{ route('product.show', $product) }}" class="block relative aspect-square overflow-hidden rounded-md bg-gray-100 border-2 border-gray-900">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
             class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
        @if ($product->is_on_sale)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-2 py-1 rotate-6 shadow-[2px_2px_0_#000]">SALE</span>
        @endif
        @if ($product->stock <= 0)
            <span class="absolute inset-0 bg-black/60 backdrop-blur-[1px] flex items-center justify-center text-sm font-bold uppercase tracking-wide text-white">Sold Out</span>
        @endif
    </a>

    @auth
        <form method="POST" action="{{ route('wishlist.toggle', $product) }}" class="absolute top-5 right-5">
            @csrf
            <button class="w-8 h-8 rounded-full bg-white/90 border border-gray-900 shadow-[2px_2px_0_#000] flex items-center justify-center hover:bg-white" title="Add to wishlist">
                <svg class="w-4 h-4 {{ auth()->user()->wishlist()->where('product_id', $product->id)->exists() ? 'text-red-600 fill-red-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </form>
    @endauth

    <div class="mt-3 flex flex-col">
        <p class="text-sm font-black uppercase tracking-wide">{{ $product->brand->name ?? '' }}</p>
        <a href="{{ route('product.show', $product) }}" class="text-sm font-semibold text-gray-900 hover:text-red-600 line-clamp-2">{{ $product->name }}</a>
        <div class="mt-2 flex items-center justify-between">
            @if ($product->is_on_sale)
                <span class="font-display text-lg text-red-600">₱{{ number_format($product->sale_price, 2) }}</span>
                <span class="text-xs text-gray-400 line-through">₱{{ number_format($product->price, 2) }}</span>
            @else
                <span class="font-display text-lg text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                <span class="text-xs font-bold uppercase tracking-wide text-gray-400">Free ship ₱3k+</span>
            @endif
        </div>
    </div>
</div>
