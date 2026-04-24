<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class StockController extends Controller
{
    public function stockIn(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.warehouse_id' => 'required|exists:warehouses,id',
                'items.*.quantity' => 'required|integer|min:1',
                'source' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $service = app(\App\Services\StockService::class);

            $transaction = $service->addStock(
                $validated['items'],
                $validated['source'] ?? 'API',
                $validated['notes'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Stock berhasil ditambahkan',
                'data' => $transaction
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function stockOut(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.warehouse_id' => 'required|exists:warehouses,id',
        'items.*.quantity' => 'required|integer|min:1',
        'source' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    $service = app(\App\Services\StockService::class);

    $transaction = $service->stockOut(
        $validated['items'],
        $validated['source'] ?? 'API',
        $validated['notes'] ?? null
    );

    return response()->json([
        'success' => true,
        'message' => 'Stock berhasil dikurangi',
        'data' => $transaction
    ]);
}
}