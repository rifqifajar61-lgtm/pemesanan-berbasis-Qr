<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitchenController extends Controller
{
    // aksi => [status asal, status tujuan, pesan sukses]
    private const TRANSITIONS = [
        'start' => ['paid',    'kitchen',   'Pesanan mulai dimasak.'],
        'ready' => ['kitchen', 'ready',     'Pesanan siap disajikan.'],
        'serve' => ['ready',   'completed', 'Pesanan sudah disajikan.'],
    ];

    public function dashboard()
    {
        $countNew        = Order::where('status', 'paid')->count();
        $countProcessing = Order::where('status', 'kitchen')->count();
        $countReady      = Order::where('status', 'ready')->count();
        $countDoneToday  = Order::where('status', 'completed')->whereDate('updated_at', today())->count();

        $newOrders = Order::with('items.menu')
            ->where('status', 'paid')
            ->oldest()
            ->limit(5)
            ->get();

        return view('kitchen.dashboard', compact(
            'countNew', 'countProcessing', 'countReady', 'countDoneToday', 'newOrders'
        ));
    }

    public function orders()
    {
        return $this->listView('paid', [
            'title'    => 'Daftar Pesanan',
            'subtitle' => 'Pesanan yang sudah dibayar dan menunggu dimasak',
            'action'   => 'start',
            'button'   => '🔥 Mulai Masak',
            'color'    => 'bg-orange-600 hover:bg-orange-700',
            'empty'    => 'Belum ada pesanan baru.',
        ]);
    }

    public function processing()
    {
        return $this->listView('kitchen', [
            'title'    => 'Proses Pesanan',
            'subtitle' => 'Pesanan yang sedang dimasak',
            'action'   => 'ready',
            'button'   => '✅ Tandai Siap',
            'color'    => 'bg-green-600 hover:bg-green-700',
            'empty'    => 'Tidak ada pesanan yang sedang dimasak.',
        ]);
    }

    public function ready()
    {
        return $this->listView('ready', [
            'title'    => 'Pesanan Siap Disajikan',
            'subtitle' => 'Pesanan selesai dimasak dan menunggu diantar / diambil',
            'action'   => 'serve',
            'button'   => '🍽️ Sudah Disajikan',
            'color'    => 'bg-blue-600 hover:bg-blue-700',
            'empty'    => 'Tidak ada pesanan yang menunggu disajikan.',
        ]);
    }

    public function history(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', today()->toDateString());
        $endDate   = $request->input('end_date', today()->toDateString());

        $orders = Order::with('items.menu')
            ->where('status', 'completed')
            ->whereDate('updated_at', '>=', $startDate)
            ->whereDate('updated_at', '<=', $endDate)
            ->when($request->filled('q'), fn ($q) => $q->where('order_code', 'like', '%' . $request->q . '%'))
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('kitchen.history', compact('orders', 'startDate', 'endDate'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:' . implode(',', array_keys(self::TRANSITIONS)),
        ]);

        [$from, $to, $message] = self::TRANSITIONS[$request->action];

        $changed = DB::transaction(function () use ($id, $from, $to) {
            $order = Order::lockForUpdate()->findOrFail($id);

            // Cegah lompat status atau diproses dua kali
            if ($order->status !== $from) {
                return false;
            }

            $order->update(['status' => $to]);

            return true;
        });

        if (!$changed) {
            return back()->with('error', 'Status pesanan sudah berubah. Halaman diperbarui.');
        }

        return back()->with('success', $message);
    }

    private function listView(string $status, array $config)
    {
        $orders = Order::with('items.menu')
            ->where('status', $status)
            ->oldest()
            ->get();

        return view('kitchen.list', compact('orders', 'config'));
    }
}