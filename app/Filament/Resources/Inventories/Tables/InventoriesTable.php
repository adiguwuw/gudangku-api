<?php

namespace App\Filament\Resources\Inventories\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InventoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable(),

                TextColumn::make('warehouse.name')
                    ->label('Gudang')
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Stok')
                    ->sortable(),

            ]);
    }
}
