<?php

namespace App\Filament\Resources\StockTransactions\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Services\StockService;
use Filament\Notifications\Notification;
use App\Filament\Resources\StockTransactions\StockTransactionResource;
use Exception;

class CreateStockTransaction extends CreateRecord
{
    protected static string $resource = StockTransactionResource::class;

   protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            $service = app(StockService::class);

            if ($data['type'] === 'IN') {
                return $service->addStock(
                    $data['items'],
                    $data['source'] ?? 'MANUAL',
                    $data['notes'] ?? null
                );
            }

            return $service->reduceStock(
                $data['items'],
                $data['source'] ?? 'MANUAL',
                $data['notes'] ?? null
            );

        } catch (Exception $e) {

            Notification::make()
                ->title('Gagal memproses transaksi')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();

            return new \App\Models\StockTransaction(); // 🔥 WAJIB
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Transaksi stok berhasil dibuat';
    }
}