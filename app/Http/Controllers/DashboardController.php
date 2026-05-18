<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show dashboard page.
     */
    public function index()
    {
        return view('pages.dashboard');
    }

    /**
     * Get dashboard statistics.
     */
    public function stats()
    {
        $today = Carbon::today();

        // Today's transactions
        $todayTrans = Transaction::whereDate('transaction_date', $today)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total')
            ->first();

        // Month's transactions
        $monthTrans = Transaction::whereMonth('transaction_date', $today->month)
            ->whereYear('transaction_date', $today->year)
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total_amount), 0) as total')
            ->first();

        // Total products
        $totalProducts = Product::count();

        // Low stock alerts
        $lowStock = Product::whereColumn('stock', '<=', 'min_stock')->count();

        // Recent transactions
        $recentTrans = Transaction::with('user')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($t) {
                return [
                    'transactions_id' => $t->transactions_id,
                    'kasir_name' => $t->user->full_name ?? 'Unknown',
                    'total_amount' => $t->total_amount,
                    'transaction_date' => $t->transaction_date,
                ];
            });

        return response()->json([
            'today' => [
                'transactions' => $todayTrans->count ?? 0,
                'sales' => (float) ($todayTrans->total ?? 0),
            ],
            'month' => [
                'transactions' => $monthTrans->count ?? 0,
                'sales' => (float) ($monthTrans->total ?? 0),
            ],
            'products' => $totalProducts,
            'lowStockAlerts' => $lowStock,
            'recentTransactions' => $recentTrans,
        ]);
    }
}
