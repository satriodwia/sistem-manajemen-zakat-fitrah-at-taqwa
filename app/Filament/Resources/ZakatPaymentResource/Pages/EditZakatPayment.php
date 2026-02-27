<?php

namespace App\Filament\Resources\ZakatPaymentResource\Pages;

use App\Filament\Resources\ZakatPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZakatPayment extends EditRecord
{
    protected static string $resource = ZakatPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
