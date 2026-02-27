<?php

namespace App\Filament\Resources\MuzakkiResource\Pages;

use App\Filament\Resources\MuzakkiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMuzakkis extends ListRecords
{
    protected static string $resource = MuzakkiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
