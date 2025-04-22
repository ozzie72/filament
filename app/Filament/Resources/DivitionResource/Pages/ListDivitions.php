<?php

namespace App\Filament\Resources\DivitionResource\Pages;

use App\Filament\Resources\DivitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDivitions extends ListRecords
{
    protected static string $resource = DivitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
