<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Show products page.
     */
    public function index()
    {
        return view('pages.products');
    }

    /**
     * Get all products.
     */
    public function getAll()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->get();
        return response()->json($products);
    }

    /**
     * Get all categories.
     */
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    /**
     * Get product by ID.
     */
    public function show($id)
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json($product);
    }

    /**
     * Store new product.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'categories_id' => $request->categories_id,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'min_stock' => $request->min_stock ?? 5,
            'unit' => $request->unit ?? 'pcs',
            'barcode' => $request->barcode,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product->load('category')
        ], 201);
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $product->update([
            'name' => $request->name,
            'categories_id' => $request->categories_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'min_stock' => $request->min_stock ?? 5,
            'unit' => $request->unit ?? 'pcs',
            'barcode' => $request->barcode,
        ]);

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'product' => $product->load('category')
        ]);
    }

    /**
     * Delete product.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $products = Product::with('category')
            ->where('name', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }
}
