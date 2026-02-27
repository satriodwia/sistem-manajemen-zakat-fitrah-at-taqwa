<?php

namespace App\Filament\Resources\SedekahPaymentResource\Pages;

use App\Filament\Resources\SedekahPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSedekahPayment extends EditRecord
{
    protected static string $resource = SedekahPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
