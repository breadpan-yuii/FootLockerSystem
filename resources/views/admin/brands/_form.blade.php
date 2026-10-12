@php($brand = $brand ?? null)

<div class="max-w-2xl bg-white rounded-xl border border-gray-200 p-6 space-y-4">
    <div>
        <label class="block text-sm font-semibold mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $brand?->name) }}" required
               class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
        @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Description</label>
        <textarea name="description" rows="3" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">{{ old('description', $brand?->description) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Logo</label>
        @if ($brand?->logo)
            <img src="{{ $brand->logo_url }}" class="w-32 h-32 object-contain rounded-md mb-2 bg-gray-100" alt="">
        @endif
        <input type="file" name="logo" accept="image/*" class="w-full text-sm">
    </div>

    <label class="flex items-center gap-2 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand?->is_active ?? true)) class="rounded text-red-600 focus:ring-red-500">
        Active
    </label>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide px-6 py-3 rounded-md text-sm">
            {{ $brand ? 'Update' : 'Create' }}
        </button>
        <a href="{{ route('admin.brands.index') }}" class="bg-gray-100 hover:bg-gray-200 font-semibold px-6 py-3 rounded-md text-sm">Cancel</a>
    </div>
</div>
