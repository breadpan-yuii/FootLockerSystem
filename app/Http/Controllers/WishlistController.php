<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $items = auth()->user()
            ->wishlist()
            ->with('product.brand', 'product.category')
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Product $product): RedirectResponse
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', "{$product->name} removed from your wishlist.");
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', "{$product->name} added to your wishlist.");
    }
}
