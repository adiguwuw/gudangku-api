<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Warehouse;
use App\Services\StockService;
use Carbon\Carbon;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(StockService::class);

        // 🔥 1. MASTER DATA
        $products = Product::factory(100)->create();
        $warehouses = Warehouse::factory(3)->create();

        // 🔥 2. INITIAL STOCK (SEMUA PRODUK PUNYA STOK)
        foreach ($products as $product) {
            $service->addStock([
                [
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouses->random()->id,
                    'quantity' => rand(50, 150),
                ]
            ], 'INITIAL', 'Stok awal');
        }

        // 🔥 3. SIMULASI 7 HARI
        for ($day = 6; $day >= 0; $day--) {

            $date = Carbon::now()->subDays($day);

            $transactionsPerDay = rand(10, 20);

            for ($i = 0; $i < $transactionsPerDay; $i++) {

                $items = [];

                $itemCount = rand(1, 4);

                for ($j = 0; $j < $itemCount; $j++) {

                    // 🔥 produk populer
                    $product = rand(1, 100) <= 40
                        ? $products->random(10)->random()
                        : $products->random();

                    $items[] = [
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouses->random()->id,
                        'quantity' => rand(1, 25),
                    ];
                }

                // 🔥 70% IN, 30% OUT
                $type = rand(1, 100) <= 70 ? 'IN' : 'OUT';

                try {
                    if ($type === 'IN') {
                        $trx = $service->addStock($items, 'SIMULATION', 'Barang Masuk');
                    } else {
                        $trx = $service->reduceStock($items, 'SIMULATION', 'Barang Keluar');
                    }

                    // 🔥 BACKDATE + JAM RANDOM
                    $dateClone = clone $date;
                    $dateClone->setTime(rand(8, 17), rand(0, 59));

                    $trx->created_at = $dateClone;
                    $trx->updated_at = $dateClone;
                    $trx->save();

                } catch (\Exception $e) {
                    continue;
                }
            }
        }
    }
}