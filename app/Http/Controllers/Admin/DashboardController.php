<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'orders' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'products' => Product::count(),
            'customers' => User::where('role', 'customer')->count(),
            'brands' => Brand::count(),
            'categories' => Category::count(),
        ];

        $recentOrders = Order::with('user')->latest('order_date')->take(8)->get();

        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        $statusCounts = collect(Order::STATUSES)
            ->mapWithKeys(fn ($status) => [$status => Order::where('status', $status)->count()]);

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'statusCounts'));
    }
}
