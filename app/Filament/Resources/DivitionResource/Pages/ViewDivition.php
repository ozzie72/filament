<?php

namespace App\Filament\Resources\DivitionResource\Pages;

use App\Filament\Resources\DivitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDivition extends ViewRecord
{
    protected static string $resource = DivitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
