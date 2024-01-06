<?php

namespace App\Filament\Resources\LandingContactResource\Pages;

use App\Filament\Resources\LandingContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListLandingContacts extends ListRecords
{
    protected static string $resource = LandingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\CreateAction::make(),
            ExportAction::make()->label('خروجی اکسل')->exports([
                ExcelExport::make('table')->fromTable(),
            ])
        ];
    }
}
