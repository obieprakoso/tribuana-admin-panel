<?php

namespace App\Filament\Admin\Resources\TransactionTypeResource\Pages;

use App\Filament\Admin\Resources\TransactionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionType extends EditRecord
{
    protected static string $resource = TransactionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 