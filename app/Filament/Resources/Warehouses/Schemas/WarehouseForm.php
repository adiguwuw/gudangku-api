<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Gudang')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Gudang')
                            ->required(),

                        Textarea::make('location')
                            ->label('Lokasi')
                            ->rows(3),
                    ])
                    ->columns(1),

            ]);
    }
}