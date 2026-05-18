<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Routes - tanpa CSRF protection untuk API login
Route::match(['get', 'post'], '/login', [AuthController::class, 'handleLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['web'])->group(function () {
    // Root redirect
    Route::get('/', function () {
        return redirect()->intended('/dashboard');
    });

    // API auth session check
    Route::get('/api/auth/session', [AuthController::class, 'session']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('restrictPage');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'stats']);

    // Products
    Route::get('/products', [ProductController::class, 'index'])->middleware('restrictPage');
    Route::get('/api/products', [ProductController::class, 'getAll']);
    Route::get('/api/categories', [ProductController::class, 'getCategories']);
    Route::get('/api/products/{id}', [ProductController::class, 'show']);
    Route::post('/api/products', [ProductController::class, 'store']);
    Route::put('/api/products/{id}', [ProductController::class, 'update']);
    Route::delete('/api/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/api/products/search', [ProductController::class, 'search']);

    // POS
    Route::get('/pos', [POSController::class, 'index'])->middleware('restrictPage');
    Route::get('/api/pos/products', [POSController::class, 'getProducts']);
    Route::post('/api/transactions', [POSController::class, 'processTransaction']);
    Route::get('/api/transactions', [POSController::class, 'getTransactions']);
    Route::get('/api/transactions/{id}', [POSController::class, 'getTransaction']);

    // Inventory (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->middleware('restrictPage');
        Route::get('/api/inventory', [InventoryController::class, 'getAll']);
        Route::get('/api/inventory/alerts', [InventoryController::class, 'alerts']);
        Route::put('/api/inventory/{id}/restock', [InventoryController::class, 'restock']);
        Route::put('/api/inventory/{id}/adjust', [InventoryController::class, 'adjust']);
        Route::get('/api/inventory/{id}/history', [InventoryController::class, 'history']);

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->middleware('restrictPage');
        Route::get('/api/reports/sales', [ReportsController::class, 'sales']);
        Route::get('/api/reports/daily', [ReportsController::class, 'daily']);
        Route::get('/api/reports/stock', [ReportsController::class, 'stock']);
        Route::get('/api/reports/top-products', [ReportsController::class, 'topProducts']);
    });
});