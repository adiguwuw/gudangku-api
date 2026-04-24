<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // 🔓 PUBLIC
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');

    // 🔐 PROTECTED
    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/stock-in', [StockController::class, 'stockIn']);
        Route::post('/stock-out', [StockController::class, 'stockOut']);
        Route::post('/transfer', [StockController::class, 'transfer'])->middleware('throttle:30,1');

        Route::apiResource('products', ProductController::class);
        Route::apiResource('warehouses', WarehouseController::class);

        Route::get('/inventories', [InventoryController::class, 'index']);
        Route::get('/low-stock', [InventoryController::class, 'lowStock']);

        Route::get('/transactions', [TransactionController::class, 'index']);
        Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    });

    // ❤️ HEALTH
    Route::get('/', function () {
        return response()->json([
            'success' => true,
            'message' => 'API Gudang aktif'
        ]);
    });

    // ❌ FALLBACK
    Route::fallback(function () {
        return response()->json([
            'success' => false,
            'message' => 'Endpoint tidak ditemukan'
        ], 404);
    });
});