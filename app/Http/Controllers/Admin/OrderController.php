<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with('user')
            ->withCount('items')
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('search'), function ($q, $search) {
                $q->where(fn ($q) => $q
                    ->where('order_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%"));
            })
            ->latest('order_date')
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('user', 'items.product.brand', 'items.variant');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', Order::STATUSES)],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Order status updated to '.ucfirst($data['status']).'.');
    }
}
