<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $averagePrice = Order::query()->sum('grand_total');
        $averagePriceFormatted = 'RP ' . number_format($averagePrice, 3);
        $totalPembeli = DB::table('orders')->distinct('nama_pelanggan')->count('nama_pelanggan');
        return [
            Stat::make('Total Pemasukan', $averagePriceFormatted)
                ->description('Pemasukan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pembeli', $totalPembeli)
                ->description('Pembeli')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}