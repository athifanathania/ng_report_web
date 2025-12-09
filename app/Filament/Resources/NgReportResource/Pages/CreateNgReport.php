<?php

namespace App\Filament\Resources\NgReportResource\Pages;

use App\Filament\Resources\NgReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNgReport extends CreateRecord
{
    protected static string $resource = NgReportResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
