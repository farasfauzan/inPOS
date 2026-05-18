<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class POSController extends Controller
{
    /**
     * Show POS page.
     */
    public function index()
    {
        return view('pages.pos');
    }

    /**
     * Get all products for POS.
     */
    public function getProducts()
    {
        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }

    /**
     * Process transaction.
     */
    public function processTransaction(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'payment_method' => 'required|in:cash,debit,qris',
            'amount_paid' => 'required|numeric',
        ]);

        $items = $request->items;
        $usersId = Session::get('users_id');

        if (!$usersId) {
            return response()->json(['error' => 'Silakan login terlebih dahulu'], 401);
        }

        return DB::transaction(function () use ($items, $request, $usersId) {
            $totalAmount = 0;
            $processedItems = [];

            // Validate stock and calculate totals
            foreach ($items as $item) {
                $product = Product::where('products_id', $item['products_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Produk ID {$item['products_id']} tidak ditemukan");
                }

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak mencukupi untuk {$product->name}. Stok tersedia: {$product->stock}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $processedItems[] = [
                    'products_id' => $product->products_id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            $paid = (float) $request->amount_paid;

            if ($paid < $totalAmount) {
                throw new \Exception('Jumlah pembayaran kurang dari total');
            }

            // Create transaction
            $transaction = Transaction::create([
                'users_id' => $usersId,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'amount_paid' => $paid,
                'change_amount' => $paid - $totalAmount,
                'transaction_date' => now(),
            ]);

            // Create details and update stock
            foreach ($processedItems as $item) {
                TransactionDetail::create([
                    'transactions_id' => $transaction->transactions_id,
                    'products_id' => $item['products_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                $product = Product::find($item['products_id']);
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $item['quantity'];

                $product->update(['stock' => $stockAfter]);

                StockHistory::create([
                    'products_id' => $item['products_id'],
                    'change_type' => 'sale',
                    'quantity_change' => -$item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_id' => $transaction->transactions_id,
                ]);
            }

            return response()->json([
                'message' => 'Transaksi berhasil',
                'transaction' => $transaction->load('details'),
            ], 201);
        });
    }

    /**
     * Get all transactions.
     */
    public function getTransactions()
    {
        $transactions = Transaction::with('user')
            ->orderBy('transaction_date', 'desc')
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
                ];
            });

        return response()->json($transactions);
    }

    /**
     * Get transaction details.
     */
    public function getTransaction($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])
            ->find($id);

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($transaction);
    }
}
