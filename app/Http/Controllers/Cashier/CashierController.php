<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function dashboard()
    {
        $paidToday = fn () => Order::where('payment_status', 'paid')->whereDate('created_at', today());

        $todayRevenue      = $paidToday()->sum('total_amount');
        $todayTransactions = $paidToday()->count();

        $unpaid = fn () => Order::where('payment_status', 'unpaid')->where('status', '!=', 'cancelled');

        $unpaidCount  = $unpaid()->count();
        $unpaidOrders = $unpaid()->oldest()->limit(10)->get();

        $recentPaid = Order::where('payment_status', 'paid')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('cashier.dashboard', compact(
            'todayRevenue', 'todayTransactions', 'unpaidCount', 'unpaidOrders', 'recentPaid'
        ));
    }

    public function scan()
    {
        return view('cashier.scan');
    }

    public function lookup(Request $request)
    {
        $request->validate(['code' => 'required|string|max:255']);

        $code = trim($request->input('code'));
        $code = basename(parse_url($code, PHP_URL_PATH) ?: $code);

        $order = Order::where('order_code', $code)->first();

        if (!$order) {
            return back()->with('error', "Pesanan dengan kode \"{$code}\" tidak ditemukan.");
        }

        return redirect()->route('cashier.process', $order->order_code);
    }

    public function process(string $orderCode)
    {
        $order = Order::with('items.menu')
            ->where('order_code', $orderCode)
            ->firstOrFail();

        return view('cashier.process', compact('order'));
    }

    public function payCash(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $total = (int) $order->total_amount;

        $request->validate([
            'cash_received' => 'required|integer|min:' . $total,
        ], [
            'cash_received.min' => 'Uang yang diterima kurang dari total tagihan.',
        ]);

        $cash = (int) $request->cash_received;

        if (!$this->markAsPaid($order, 'cash', $cash, $cash - $total)) {
            return redirect()->route('cashier.process', $order->order_code)
                ->with('error', 'Pesanan ini sudah dibayar atau dibatalkan.');
        }

        return redirect()->route('cashier.success', $order->id);
    }

    public function payQris(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$this->markAsPaid($order, 'qris')) {
            return redirect()->route('cashier.process', $order->order_code)
                ->with('error', 'Pesanan ini sudah dibayar atau dibatalkan.');
        }

        return redirect()->route('cashier.success', $order->id);
    }

    public function success($id)
    {
        $order = Order::with('items.menu')
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        return view('cashier.success', compact('order'));
    }

    public function history(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', today()->toDateString());
        $endDate   = $request->input('end_date', today()->toDateString());

        $base = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->when($request->filled('method'), fn ($q) => $q->where('payment_method', $request->method))
            ->when($request->filled('q'), fn ($q) => $q->where('order_code', 'like', '%' . $request->q . '%'));

        $totalRevenue = (clone $base)->sum('total_amount');
        $totalCount   = (clone $base)->count();

        $orders = (clone $base)
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('cashier.history', compact('orders', 'startDate', 'endDate', 'totalRevenue', 'totalCount'));
    }

    private function markAsPaid(Order $order, string $method, ?int $cash = null, ?int $change = null): bool
    {
        return DB::transaction(function () use ($order, $method, $cash, $change) {
            $locked = Order::lockForUpdate()->find($order->id);

            if ($locked->payment_status === 'paid' || $locked->status === 'cancelled') {
                return false;
            }

            $locked->update([
                'payment_status' => 'paid',
                'status'         => 'paid',
                'payment_method' => $method,
                'cash_amount'    => $cash,
                'change_amount'  => $change,
            ]);

            return true;
        });
    }
}