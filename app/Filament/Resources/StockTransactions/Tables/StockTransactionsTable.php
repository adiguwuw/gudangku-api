<?php

namespace App\Filament\Resources\StockTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder; // ✅ FIX DI SINI

class StockTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('transaction_code')
                    ->label('Kode'),

                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'IN',
                        'danger' => 'OUT',
                    ]),

                TextColumn::make('source'),

                TextColumn::make('created_at')
                    ->dateTime(),

            ])

            ->filters([

                SelectFilter::make('type')
                    ->options([
                        'IN' => 'Stock In',
                        'OUT' => 'Stock Out',
                    ]),

                SelectFilter::make('source')
                    ->options([
                        'MANUAL' => 'Manual',
                        'INITIAL' => 'Initial',
                        'SIMULATION' => 'Simulation',
                        'TRANSFER' => 'Transfer',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'],
                                fn ($q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when($data['until'],
                                fn ($q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    }),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}