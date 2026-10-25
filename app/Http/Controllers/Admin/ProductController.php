<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'category'])
            ->when($request->query('search'), fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $product = Product::create($this->attributes($data));

        $this->syncVariants($product, $request);
        $this->syncGallery($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $product->load('brand', 'category', 'images', 'variants');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $product->load('images', 'variants');

        return view('admin.products.edit', $this->formData() + compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);

        $product->update($this->attributes($data, $product));

        $this->syncVariants($product, $request);
        $this->syncGallery($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && ! str_starts_with($product->image, 'http')) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function formData(): array
    {
        return [
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ];
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'brand_id' => ['required', 'exists:brands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'gender' => ['required', 'in:men,women,kids,unisex'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:4096'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'max:4096'],
            'variants' => ['nullable', 'array'],
            'variants.*.size' => ['nullable', 'string', 'max:50'],
            'variants.*.color' => ['nullable', 'string', 'max:50'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function attributes(array $data, ?Product $product = null): array
    {
        $attributes = [
            'brand_id' => $data['brand_id'],
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name'], $product),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'stock' => $data['stock'],
            'gender' => $data['gender'],
            'is_featured' => $data['is_featured'] ?? false,
            'is_active' => $data['is_active'] ?? false,
        ];

        if (request()->hasFile('image')) {
            if ($product?->image && ! str_starts_with($product->image, 'http')) {
                Storage::disk('public')->delete($product->image);
            }
            $attributes['image'] = request()->file('image')->store('products', 'public');
        }

        return $attributes;
    }

    private function syncVariants(Product $product, Request $request): void
    {
        $product->variants()->delete();

        foreach ($request->input('variants', []) as $variant) {
            if (empty($variant['size'])) {
                continue;
            }

            $product->variants()->create([
                'size' => $variant['size'],
                'color' => $variant['color'] ?? null,
                'sku' => $variant['sku'] ?? null,
                'stock' => $variant['stock'] ?? 0,
            ]);
        }
    }

    private function syncGallery(Product $product, Request $request): void
    {
        foreach ($request->file('gallery', []) as $file) {
            $product->images()->create([
                'image' => $file->store('products', 'public'),
                'is_primary' => $product->images()->count() === 0,
            ]);
        }
    }

    private function uniqueSlug(string $name, ?Product $product = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->when($product, fn ($q) => $q->where('id', '!=', $product->id))->exists()) {
            $slug = "{$original}-".$count++;
        }

        return $slug;
    }
}
