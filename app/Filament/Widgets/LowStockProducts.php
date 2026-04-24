<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Inventory;

class LowStockProducts extends BaseWidget
{
    protected static ?string $heading = '⚠️ Stok Menipis';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Inventory::query()
            ->with(['product', 'warehouse'])
            ->whereHas('product', function ($query) {
                $query->whereColumn('inventories.quantity', '<=', 'products.minimum_stock');
            });
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('product.name')
                ->label('Produk'),

            Tables\Columns\TextColumn::make('warehouse.name')
                ->label('Gudang'),

            Tables\Columns\TextColumn::make('quantity')
                ->label('Stok')
                ->badge()
                ->color('danger'),
        ];
    }
}