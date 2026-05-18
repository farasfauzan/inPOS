<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionDetail;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Show reports page.
     */
    public function index()
    {
        return view('pages.reports');
    }

    /**
     * Get sales report.
     */
    public function sales(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->start_date) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'transactions_id' => $t->transactions_id,
                    'kasir_name' => $t->user->full_name ?? 'Unknown',
                    'total_amount' => $t->total_amount,
                    'payment_method' => $t->payment_method,
                    'amount_paid' => $t->amount_paid,
                    'change_amount' => $t->change_amount,
                    'transaction_date' => $t->transaction_date,
                    'total_items' => $t->details->count(),
                ];
            });

        $totalSales = $transactions->sum('total_amount');

        return response()->json([
            'transactions' => $transactions,
            'summary' => [
                'total_transactions' => $transactions->count(),
                'total_sales' => $totalSales,
            ],
        ]);
    }

    /**
     * Get daily report.
     */
    public function daily()
    {
        $daily = Transaction::selectRaw('DATE(transaction_date) as date, COUNT(*) as total_transactions, SUM(total_amount) as total_sales')
            ->where('transaction_date', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($daily);
    }

    /**
     * Get stock report.
     */
    public function stock()
    {
        $products = Product::with('category')
            ->orderBy('stock', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'products_id' => $p->products_id,
                    'name' => $p->name,
                    'category_name' => $p->category->name ?? null,
                    'stock' => $p->stock,
                    'min_stock' => $p->min_stock,
                    'unit' => $p->unit,
                    'stock_status' => $p->stock_status,
                ];
            });

        $categories = Category::orderBy('name')->get();

        return response()->json([
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    /**
     * Get top products.
     */
    public function topProducts(Request $request)
    {
        $query = TransactionDetail::selectRaw('products.products_id, products.name, categories.name as category_name, SUM(transaction_details.quantity) as total_sold, SUM(transaction_details.subtotal) as total_revenue')
            ->join('products', 'transaction_details.products_id', '=', 'products.products_id')
            ->leftJoin('categories', 'products.categories_id', '=', 'categories.categories_id')
            ->join('transactions', 'transaction_details.transactions_id', '=', 'transactions.transactions_id')
            ->groupBy('products.products_id', 'products.name', 'categories.name');

        if ($request->start_date) {
            $query->whereDate('transactions.transaction_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('transactions.transaction_date', '<=', $request->end_date);
        }

        $products = $query->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
