<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockService
{
    public function stockIn(array $items, string $source = 'MANUAL', ?string $notes = null)
    {
        return $this->process('IN', $items, $source, $notes);
    }

    public function stockOut(array $items, string $source = 'MANUAL', ?string $notes = null)
    {
        return $this->process('OUT', $items, $source, $notes);
    }

    public function transfer(array $items, string $source = 'TRANSFER', ?string $notes = null)
    {
        return DB::transaction(function () use ($items, $source, $notes) {

            foreach ($items as $item) {

                if ($item['from_warehouse_id'] === $item['to_warehouse_id']) {
                    throw new Exception('Gudang asal dan tujuan tidak boleh sama');
                }

                // OUT dari gudang asal
                $this->process('OUT', [[
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['from_warehouse_id'],
                    'quantity' => $item['quantity'],
                ]], $source, 'Transfer keluar');

                // IN ke gudang tujuan
                $this->process('IN', [[
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['to_warehouse_id'],
                    'quantity' => $item['quantity'],
                ]], $source, 'Transfer masuk');
            }

            return true;
        });
    }

    private function process(string $type, array $items, string $source, ?string $notes)
    {
        $this->validateItems($items);

        return DB::transaction(function () use ($type, $items, $source, $notes) {

            $transaction = StockTransaction::create([
                'type' => $type,
                'source' => $source,
                'notes' => $notes,
                'transaction_code' => 'TRX-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(5)),
            ]);

            foreach ($items as $item) {

                $this->validateItem($item);

                $inventory = Inventory::firstOrCreate(
                    [
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $item['warehouse_id'],
                    ],
                    ['quantity' => 0]
                );

                if ($type === 'IN') {
                    $inventory->increment('quantity', $item['quantity']);
                } else {

                    if ($inventory->quantity < $item['quantity']) {
                        throw new Exception('Stok tidak mencukupi');
                    }

                    $inventory->decrement('quantity', $item['quantity']);
                }

                StockTransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'warehouse_id' => $item['warehouse_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $transaction->load('items');
        });
    }

    private function validateItems(array $items)
    {
        if (empty($items)) {
            throw new Exception('Items tidak boleh kosong');
        }
    }

    private function validateItem(array $item)
    {
        if (
            empty($item['product_id']) ||
            empty($item['warehouse_id']) ||
            empty($item['quantity'])
        ) {
            throw new Exception('Data item tidak lengkap');
        }

        if ($item['quantity'] <= 0) {
            throw new Exception('Quantity harus lebih dari 0');
        }
    }
}