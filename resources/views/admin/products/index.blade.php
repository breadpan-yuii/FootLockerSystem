@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products"
                   class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
            <button class="bg-gray-900 text-white text-sm font-semibold px-4 rounded-md">Search</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-md text-sm">+ Add Product</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100 bg-gray-50">
                        <th class="px-5 py-3 font-semibold">Product</th>
                        <th class="px-5 py-3 font-semibold">Brand</th>
                        <th class="px-5 py-3 font-semibold">Category</th>
                        <th class="px-5 py-3 font-semibold">Price</th>
                        <th class="px-5 py-3 font-semibold">Stock</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" class="w-10 h-10 rounded object-cover bg-gray-100" alt="">
                                    <span class="font-semibold text-gray-800 line-clamp-1">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $product->brand->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $product->category->name ?? '—' }}</td>
                            <td class="px-5 py-3 font-semibold">₱{{ number_format($product->price, 2) }}</td>
                            <td class="px-5 py-3">
                                <span class="{{ $product->stock > 0 ? 'text-gray-700' : 'text-red-600 font-semibold' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ $product->is_active ? 'Active' : 'Hidden' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-semibold text-blue-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Delete this product?')">
                                    @csrf @method('DELETE')
                                    <button class="text-sm font-semibold text-red-600 hover:underline ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-500">No products found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $products->links() }}</div>
    </div>
@endsection
