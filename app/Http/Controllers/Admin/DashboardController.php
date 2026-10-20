<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $totalMenus = Menu::count();

        return view('admin.dashboard', compact('totalRevenue', 'totalOrders', 'totalMenus'));
    }
}