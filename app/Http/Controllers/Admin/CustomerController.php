<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->when($request->query('search'), fn ($q, $search) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        abort_unless($customer->role === 'customer', 404);

        $customer->load(['orders' => fn ($q) => $q->latest('order_date')]);

        return view('admin.customers.show', compact('customer'));
    }
}
