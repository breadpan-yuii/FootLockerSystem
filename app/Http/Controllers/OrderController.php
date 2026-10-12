<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()
            ->orders()
            ->withCount('items')
            ->latest('order_date')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        $order->load('items.product.brand', 'items.variant');

        return view('orders.show', compact('order'));
    }

    public function trackForm(): View
    {
        return view('orders.track');
    }

    public function track(Request $request): View
    {
        $data = $request->validate([
            'order_number' => ['required', 'string'],
        ]);

        $order = Order::with('items.product')
            ->where('order_number', $data['order_number'])
            ->first();

        return view('orders.track', [
            'order' => $order,
            'searched' => true,
            'orderNumber' => $data['order_number'],
        ]);
    }
}
