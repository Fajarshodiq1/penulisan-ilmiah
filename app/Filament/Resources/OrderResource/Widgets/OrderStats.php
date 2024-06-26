<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
Use App\Models\Order;
use Filament\Widgets\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $averagePrice = Order::query()->sum('grand_total');
        $averagePriceFormatted = 'RP ' . number_format($averagePrice, 3);

        return [
            Stat::make('Total Pemasukan', $averagePriceFormatted),
        ];
    }
}