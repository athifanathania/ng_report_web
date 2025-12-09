<?php

namespace App\Filament\Resources\NgReportResource\Pages;

use App\Filament\Resources\NgReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNgReport extends EditRecord
{
    protected static string $resource = NgReportResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
