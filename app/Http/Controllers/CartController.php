<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->cart()->load(['items.product.brand', 'items.variant']);

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::where('is_active', true)->findOrFail($data['product_id']);

        $variant = null;
        if (! empty($data['product_variant_id'])) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->findOrFail($data['product_variant_id']);
        }

        $available = $variant?->stock ?? $product->stock;
        if ($available < $data['quantity']) {
            return back()->with('error', 'Sorry, there is not enough stock for that item.');
        }

        $cart = $this->cart();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        if ($item) {
            $item->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'quantity' => $data['quantity'],
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', "{$product->name} was added to your bag.");
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorizeItem($cartItem);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $cartItem->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'Your bag was updated.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $this->authorizeItem($cartItem);

        $cartItem->delete();

        return back()->with('success', 'Item removed from your bag.');
    }

    private function cart(): Cart
    {
        return auth()->user()->cart()->firstOrCreate();
    }

    private function authorizeItem(CartItem $cartItem): void
    {
        abort_unless($cartItem->cart->user_id === auth()->id(), 403);
    }
}
