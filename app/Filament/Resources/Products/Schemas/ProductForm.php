<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Produk')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required(),

                        TextInput::make('sku')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->formatStateUsing(fn ($state) => strtoupper($state)),

                        TextInput::make('barcode')
                            ->unique(ignoreRecord: true),

                        TextInput::make('unit')
                            ->placeholder('pcs, box, dll'),
                    ])
                    ->columns(2),

                Section::make('Harga')
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga Jual')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),

                        TextInput::make('cost_price')
                            ->label('Harga Modal')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->columns(2),

                Section::make('Stok & Detail')
                    ->schema([
                        TextInput::make('minimum_stock')
                            ->label('Minimum Stok')
                            ->numeric()
                            ->default(0),

                        TextInput::make('brand'),

                        TextInput::make('weight')
                            ->numeric()
                            ->suffix('gram'),
                    ])
                    ->columns(3),

                Section::make('Deskripsi')
                    ->schema([
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}