<?php

namespace App\Filament\Resources\StockTransactions\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class StockTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Transaksi')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'IN' => 'Stock In',
                                'OUT' => 'Stock Out',
                            ])
                            ->required(),

                        Select::make('source')
                            ->options([
                                'MANUAL' => 'Manual',
                                'POS' => 'POS',
                                'ECOMMERCE' => 'E-Commerce',
                            ])
                            ->default('MANUAL'),

                        Textarea::make('notes'),
                    ])
                    ->columns(2),

                Section::make('Item Produk')
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required(),

                                Select::make('warehouse_id')
                                    ->relationship('warehouse', 'name')
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(3)
                            ->required(),
                    ])
            ]);
    }
}