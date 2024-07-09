<?php

namespace App\Filament\Panels;

use Filament\Navigation\NavigationItem;
use Filament\Panel;

class CustomPanel
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->brandName('Filament Demo')
        ->logo('<h1>Filament Demo</h1>')
        ->theme('light')
        ->navigationItems([
            NavigationItem::make('Dashboard')
                ->url(route('filament.pages.dashboard'))
                ->icon('heroicon-o-home')
                ->sort(1),
        ]);
    }
}