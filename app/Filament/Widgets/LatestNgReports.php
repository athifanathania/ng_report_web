<?php

namespace App\Filament\Widgets;

use App\Models\NgReport;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestNgReports extends BaseWidget
{
    protected static ?int $sort = 5; // Tampil di bawah chart
    protected int | string | array $columnSpan = 'full'; // Lebar penuh

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Ambil 5 data terakhir
                NgReport::query()->latest('created_at')->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('input_date')->date()->label('Tgl'),
                Tables\Columns\TextColumn::make('supplier.name')->label('Supplier'),
                Tables\Columns\TextColumn::make('part.part_name')->label('Part'),
                Tables\Columns\TextColumn::make('ng_category')
                    ->badge()
                    ->color('danger')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'DRAFT',
                        'success' => 'SENT',
                    ]),
            ])
            ->paginated(false); // Matikan pagination agar ringkas
    }
}