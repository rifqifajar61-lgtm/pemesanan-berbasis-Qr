<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        // Query dasar: pesanan lunas dalam rentang tanggal
        $paidOrders = fn () => Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRevenue = $paidOrders()->sum('total_amount');
        $totalOrders  = $paidOrders()->count();
        $averageOrder = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;

        $totalItemsSold = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('payment_status', 'paid')
              ->whereDate('created_at', '>=', $startDate)
              ->whereDate('created_at', '<=', $endDate);
        })->sum('quantity');

        // Penjualan per hari
        $dailySales = $paidOrders()
            ->select(
                DB::raw('DATE(created_at) as sale_date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderByDesc('sale_date')
            ->get();

        // 10 menu terlaris
        $topMenus = OrderItem::with('menu')
            ->select(
                'menu_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('payment_status', 'paid')
                  ->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'averageOrder',
            'totalItemsSold',
            'dailySales',
            'topMenus'
        ));
    }
}