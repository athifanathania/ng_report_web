<?php

namespace App\Filament\Widgets;

use App\Models\NgReport;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        return [
            // 1. TOTAL SUDAH TERKIRIM (Selama ini)
            Stat::make('Total Laporan Terkirim', NgReport::where('status', 'SENT')->count())
                ->description('Akumulasi laporan sukses terkirim')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'), // Hijau karena sukses

            // 2. TERKIRIM BULAN INI
            Stat::make('Terkirim Bulan Ini', NgReport::where('status', 'SENT')
                ->whereMonth('input_date', now()->month)
                ->whereYear('input_date', now()->year)
                ->count())
                ->description('Bulan ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'), // Biru

            // 3. TERKIRIM MINGGU INI
            Stat::make('Terkirim 7 Hari Terakhir', NgReport::where('status', 'SENT')
                ->where('input_date', '>=', now()->subDays(7)) // <--- UBAH INI
                ->count())
                ->description('7 hari ke belakang')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),

            // 4. PENDING (DRAFT) - Sisa pekerjaan
            Stat::make('Pending (Draft)', NgReport::where('status', 'DRAFT')->count())
                ->description('Menunggu dikirim')
                ->descriptionIcon('heroicon-m-inbox-stack')
                ->color('warning'), // Kuning karena pending

            // 5. TOTAL SUPPLIER
            Stat::make('Total Suppliers', Supplier::count())
                ->description('Database supplier')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('gray'),
        ];
    }
}