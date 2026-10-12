@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <form method="GET" class="flex gap-2 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email"
               class="rounded-md border-gray-300 text-sm focus:border-red-500 focus:ring-red-500">
        <button class="bg-gray-900 text-white text-sm font-semibold px-4 rounded-md">Search</button>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100 bg-gray-50">
                    <th class="px-5 py-3 font-semibold">Name</th>
                    <th class="px-5 py-3 font-semibold">Email</th>
                    <th class="px-5 py-3 font-semibold">Phone</th>
                    <th class="px-5 py-3 font-semibold">Orders</th>
                    <th class="px-5 py-3 font-semibold">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="px-5 py-3 font-semibold text-gray-800">{{ $customer->name }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $customer->email }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $customer->phone ?: '—' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $customer->orders_count }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.customers.show', $customer) }}" class="text-sm font-semibold text-blue-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $customers->links() }}</div>
    </div>
@endsection
