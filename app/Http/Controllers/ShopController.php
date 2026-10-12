<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->filter($request->only('search', 'brand', 'category', 'gender', 'sort', 'min_price', 'max_price'))
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', $this->sharedData($request) + [
            'products' => $products,
            'heading' => 'All Shoes',
        ]);
    }

    public function search(Request $request): View
    {
        $request->merge(['search' => $request->query('q')]);

        $products = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->filter($request->only('search', 'brand', 'category', 'gender', 'sort', 'min_price', 'max_price'))
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', $this->sharedData($request) + [
            'products' => $products,
            'heading' => 'Search results for "'.e($request->query('q')).'"',
        ]);
    }

    public function category(Request $request, Category $category): View
    {
        $request->merge(['category' => $category->slug]);

        $products = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->filter($request->only('brand', 'gender', 'sort', 'min_price', 'max_price'))
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', $this->sharedData($request) + [
            'products' => $products,
            'heading' => $category->name,
            'activeCategory' => $category,
        ]);
    }

    public function brand(Request $request, Brand $brand): View
    {
        $request->merge(['brand' => $brand->slug]);

        $products = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->where('brand_id', $brand->id)
            ->filter($request->only('category', 'gender', 'sort', 'min_price', 'max_price'))
            ->paginate(12)
            ->withQueryString();

        return view('shop.index', $this->sharedData($request) + [
            'products' => $products,
            'heading' => $brand->name,
            'activeBrand' => $brand,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['brand', 'category', 'images', 'variants']);

        $related = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('category_id', $product->category_id)
                    ->orWhere('brand_id', $product->brand_id);
            })
            ->take(4)
            ->get();

        $inWishlist = auth()->check()
            && auth()->user()->wishlist()->where('product_id', $product->id)->exists();

        return view('shop.show', compact('product', 'related', 'inWishlist'));
    }

    /**
     * @return array{brands: \Illuminate\Support\Collection, categories: \Illuminate\Support\Collection}
     */
    private function sharedData(Request $request): array
    {
        return [
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ];
    }
}
