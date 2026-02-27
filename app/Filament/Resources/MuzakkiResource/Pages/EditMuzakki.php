<?php

namespace App\Filament\Resources\MuzakkiResource\Pages;

use App\Filament\Resources\MuzakkiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMuzakki extends EditRecord
{
    protected static string $resource = MuzakkiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
