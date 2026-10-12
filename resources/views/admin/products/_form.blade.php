@php($product = $product ?? null)
@php($variantData = $product ? $product->variants->map(fn ($v) => ['size' => $v->size, 'color' => $v->color, 'sku' => $v->sku, 'stock' => $v->stock])->all() : [])

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-bold text-gray-800">Product Details</h2>

            <div>
                <label class="block text-sm font-semibold mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $product?->name) }}" required
                       class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">{{ old('description', $product?->description) }}</textarea>
                @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Brand</label>
                    <select name="brand_id" required class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                        <option value="">Select brand</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Category</label>
                    <select name="category_id" required class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Price (₱)</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product?->price) }}" required
                           class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                    @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Sale Price (₱)</label>
                    <input type="number" step="0.01" min="0" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}"
                           class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                    @error('sale_price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Stock</label>
                    <input type="number" min="0" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" required
                           class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                    @error('stock') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Gender</label>
                    <select name="gender" class="w-full rounded-md border-gray-300 focus:border-red-500 focus:ring-red-500">
                        @foreach (['men', 'women', 'kids', 'unisex'] as $gender)
                            <option value="{{ $gender }}" @selected(old('gender', $product?->gender ?? 'unisex') === $gender)>{{ ucfirst($gender) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-6">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured)) class="rounded text-red-600 focus:ring-red-500">
                    Featured
                </label>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="rounded text-red-600 focus:ring-red-500">
                    Active (visible in store)
                </label>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6" x-data="{
            variants: @js(old('variants', $variantData))
        }">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Sizes / Variants</h2>
                <button type="button" @click="variants.push({ size: '', color: '', sku: '', stock: 0 })"
                        class="text-sm font-semibold text-red-600 hover:underline">+ Add variant</button>
            </div>
            <template x-if="variants.length === 0">
                <p class="text-sm text-gray-500">No variants. Add sizes if this product comes in multiple options.</p>
            </template>
            <div class="space-y-3">
                <template x-for="(variant, index) in variants" :key="index">
                    <div class="grid grid-cols-12 gap-2 items-center">
                        <input type="text" :name="`variants[${index}][size]`" x-model="variant.size" placeholder="Size"
                               class="col-span-3 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <input type="text" :name="`variants[${index}][color]`" x-model="variant.color" placeholder="Color"
                               class="col-span-3 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <input type="text" :name="`variants[${index}][sku]`" x-model="variant.sku" placeholder="SKU"
                               class="col-span-3 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" min="0" placeholder="Stock"
                               class="col-span-2 rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
                        <button type="button" @click="variants.splice(index, 1)" class="col-span-1 text-red-600 font-bold">×</button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-800 mb-3">Main Image</h2>
            @if ($product?->image)
                <img src="{{ $product->image_url }}" class="w-full h-40 object-cover rounded-md mb-3 bg-gray-100" alt="">
            @endif
            <input type="file" name="image" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-1">Recommended: square JPG/PNG, up to 4MB.</p>
            @error('image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-bold text-gray-800 mb-3">Gallery Images</h2>
            @if ($product?->images->isNotEmpty())
                <div class="grid grid-cols-3 gap-2 mb-3">
                    @foreach ($product->images as $image)
                        <img src="{{ $image->image_url }}" class="w-full h-16 object-cover rounded bg-gray-100" alt="">
                    @endforeach
                </div>
            @endif
            <input type="file" name="gallery[]" accept="image/*" multiple class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-1">You can select multiple images at once.</p>
            @error('gallery.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 flex flex-col gap-3">
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold uppercase tracking-wide py-3 rounded-md text-sm">
                {{ $product ? 'Update Product' : 'Create Product' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="w-full text-center bg-gray-100 hover:bg-gray-200 font-semibold py-3 rounded-md text-sm">Cancel</a>
        </div>
    </div>
</div>
