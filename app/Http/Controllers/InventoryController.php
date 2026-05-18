<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Show inventory page.
     */
    public function index()
    {
        return view('pages.inventory');
    }

    /**
     * Get all inventory data.
     */
    public function getAll()
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

        return response()->json($products);
    }

    /**
     * Get low stock alerts.
     */
    public function alerts()
    {
        $products = Product::with('category')
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        return response()->json($products);
    }

    /**
     * Restock product.
     */
    public function restock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore + $request->quantity;

            $product->update(['stock' => $stockAfter]);

            StockHistory::create([
                'products_id' => $product->products_id,
                'change_type' => 'restock',
                'quantity_change' => $request->quantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $request->note ?? 'Restock',
            ]);

            return response()->json([
                'message' => 'Stok berhasil ditambahkan',
                'product' => $product->load('category'),
            ]);
        });
    }

    /**
     * Get stock history for a product.
     */
    public function history($id)
    {
        $history = StockHistory::where('products_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($history);
    }

    /**
     * Adjust stock.
     */
    public function adjust(Request $request, $id)
    {
        $request->validate([
            'stock_after' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['error' => 'Produk tidak ditemukan'], 404);
            }

            $stockBefore = $product->stock;
            $stockAfter = $request->stock_after;
            $quantityChange = $stockAfter - $stockBefore;

            $product->update(['stock' => $stockAfter]);

            StockHistory::create([
                'products_id' => $product->products_id,
                'change_type' => 'adjustment',
                'quantity_change' => $quantityChange,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $request->note ?? 'Adjustment',
            ]);

            return response()->json([
                'message' => 'Stok berhasil disesuaikan',
                'product' => $product->load('category'),
            ]);
        });
    }
}
