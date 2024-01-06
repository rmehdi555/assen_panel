<?php

namespace App\Filament\Resources\LandingContactResource\Pages;

use App\Filament\Resources\LandingContactResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLandingContact extends EditRecord
{
    protected static string $resource = LandingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
