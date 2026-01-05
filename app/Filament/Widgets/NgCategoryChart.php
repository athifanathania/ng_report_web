<?php

namespace App\Filament\Widgets;

use App\Models\NgReport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class NgCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Kategori NG Terbanyak';
    protected static ?int $sort = 3; // Urutan tampilan

    protected function getData(): array
    {
        // Mengambil data jumlah per kategori
        $data = NgReport::select('ng_category', DB::raw('count(*) as total'))
            ->groupBy('ng_category')
            ->pluck('total', 'ng_category')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'NG Reports',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#f87171', // Red
                        '#fbbf24', // Amber
                        '#34d399', // Emerald
                        '#60a5fa', // Blue
                        '#a78bfa', // Violet
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // Tampilan donat lebih modern
    }
}