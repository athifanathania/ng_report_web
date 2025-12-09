<?php

namespace App\Filament\Resources\NgReportResource\Pages;

use App\Filament\Resources\NgReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNgReports extends ListRecords
{
    protected static string $resource = NgReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
