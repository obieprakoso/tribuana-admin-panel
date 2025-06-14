<?php

namespace App\Filament\Admin\Resources\TransactionTypeResource\Pages;

use App\Filament\Admin\Resources\TransactionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionTypes extends ListRecords
{
    protected static string $resource = TransactionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 