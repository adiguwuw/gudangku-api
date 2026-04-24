<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\StockTransaction;
use Carbon\Carbon;

class StockMovementChart extends ChartWidget
{
    protected ?string $heading = 'Pergerakan Stok';

    // 🔥 FILTER OPTIONS
    protected function getFilters(): ?array
    {
        return [
            '7' => '7 Hari',
            '30' => '30 Hari',
            '365' => '1 Tahun',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 7);

        $startDate = Carbon::now()->subDays($days);

        $labels = [];
        $inData = [];
        $outData = [];

        for ($i = $days - 1; $i >= 0; $i--) {

            $date = Carbon::now()->subDays($i)->format('Y-m-d');

            $labels[] = Carbon::parse($date)->format('d M');

            $inData[] = StockTransaction::where('type', 'IN')
                ->whereDate('created_at', $date)
                ->sum('id'); // 🔥 nanti kita improve

            $outData[] = StockTransaction::where('type', 'OUT')
                ->whereDate('created_at', $date)
                ->sum('id'); // 🔥 nanti kita improve
        }

        return [
            'datasets' => [
                [
                    'label' => 'Stock In',
                    'data' => $inData,
                ],
                [
                    'label' => 'Stock Out',
                    'data' => $outData,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}