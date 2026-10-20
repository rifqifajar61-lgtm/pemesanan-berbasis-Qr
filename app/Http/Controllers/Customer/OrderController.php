<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type'    => 'required|in:dine_in,takeaway',
            'table_number'  => 'nullable|required_if:order_type,dine_in|string',
            'cart'          => 'required|string',
        ]);

        $cart = json_decode($request->cart, true);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang belanja kosong.');
        }

        $orderCode = 'ORD-' . strtoupper(Str::random(8));
        $totalAmount = 0;

        foreach ($cart as $item) {
            $menu = Menu::findOrFail($item['id']);
            $totalAmount += $menu->price * $item['quantity'];
        }

        $order = Order::create([
            'order_code'     => $orderCode,
            'customer_name'  => $request->customer_name,
            'order_type'     => $request->order_type,
            'table_number'   => $request->order_type === 'dine_in' ? $request->table_number : null,
            'total_amount'   => $totalAmount,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($cart as $item) {
            $menu = Menu::findOrFail($item['id']);
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id'  => $menu->id,
                'quantity' => $item['quantity'],
                'price'    => $menu->price,
                'subtotal' => $menu->price * $item['quantity'],
            ]);
        }

        return redirect()->route('order.show', $order->order_code);
    }

    public function show($orderCode)
    {
        $order = Order::with('items.menu')->where('order_code', $orderCode)->firstOrFail();
        return view('customer.order-details', compact('order'));
    }
}