<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->when($request->filled('status'), fn ($q) => $q->where('payment_status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Sesuaikan nama relasi dengan model kamu (items / orderItems)
        $order->load('items.menu');

        return view('admin.orders.show', compact('order'));
    }
}