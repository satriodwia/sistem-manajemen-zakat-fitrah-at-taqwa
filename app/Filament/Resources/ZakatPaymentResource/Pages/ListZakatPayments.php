<?php

namespace App\Filament\Resources\ZakatPaymentResource\Pages;

use App\Filament\Resources\ZakatPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListZakatPayments extends ListRecords
{
    protected static string $resource = ZakatPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
