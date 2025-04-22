<?php

namespace App\Filament\Resources\DivitionResource\Pages;

use App\Filament\Resources\DivitionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDivition extends EditRecord
{
    protected static string $resource = DivitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
