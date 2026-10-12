@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">{{ $categories->total() }} categories</p>
        <a href="{{ route('admin.categories.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-md text-sm">+ Add Category</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100 bg-gray-50">
                    <th class="px-5 py-3 font-semibold">Name</th>
                    <th class="px-5 py-3 font-semibold">Gender</th>
                    <th class="px-5 py-3 font-semibold">Products</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                @if ($category->image)
                                    <img src="{{ $category->image_url }}" class="w-9 h-9 rounded object-cover bg-gray-100" alt="">
                                @endif
                                <span class="font-semibold text-gray-800">{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 capitalize">{{ $category->gender }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $category->products_count }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">{{ $category->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-sm font-semibold text-blue-600 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category? Products in it will also be removed.')">
                                @csrf @method('DELETE')
                                <button class="text-sm font-semibold text-red-600 hover:underline ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-500">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    </div>
@endsection
