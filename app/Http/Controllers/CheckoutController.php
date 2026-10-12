<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart = auth()->user()->cart()->with('items.product.brand', 'items.variant')->firstOrCreate();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your bag is empty.');
        }

        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'in:Cash on Delivery,GCash,Bank Transfer'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $cart = auth()->user()->cart()->with('items.product', 'items.variant')->firstOrCreate();

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your bag is empty.');
        }

        $order = DB::transaction(function () use ($cart, $data) {
            $subtotal = $cart->items->sum(fn ($item) => $item->product->display_price * $item->quantity);
            $shipping = $subtotal >= 3000 ? 0 : 150;

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'FL-'.strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping,
                'total_amount' => $subtotal + $shipping,
                'status' => 'pending',
                'order_date' => now(),
                'full_name' => $data['full_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $price = $item->product->display_price;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $price * $item->quantity,
                ]);

                if ($item->variant) {
                    $item->variant()->decrement('stock', $item->quantity);
                }
                $item->product()->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()
            ->route('orders.show', $order)
            ->with('success', "Order {$order->order_number} placed successfully!");
    }
}
