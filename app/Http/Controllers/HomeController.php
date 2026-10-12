<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featured = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $newArrivals = Product::with(['brand', 'category'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('home', compact('featured', 'newArrivals', 'brands', 'categories'));
    }
}
