<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeHeader extends Widget
{
    protected static string $view = 'filament.widgets.welcome-header';

    // Agar widget melebar penuh
    protected int | string | array $columnSpan = 'full';

    // Agar widget ini muncul DI PALING ATAS (Urutan 1)
    protected static ?int $sort = 1;
}