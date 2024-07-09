<?php

namespace App\Filament\Widgets;
 
use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Carbon\Carbon;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Blog Posts';
 
    protected function getData(): array
    {
        // Ambil data pemasukan berdasarkan bulan dari pesanan
        $data = Order::query()
            ->selectRaw('MONTH(created_at) as month, SUM(grand_total) as total_income')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Inisialisasi data datasets dan labels
        $datasets = [
            [
                'label' => 'Income',
                'data' => $data->pluck('total_income')->toArray(),
            ],
        ];

        $labels = $data->map(function ($item) {
            return Carbon::create(null, $item->month, 1)->format('M');
        })->toArray();

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}