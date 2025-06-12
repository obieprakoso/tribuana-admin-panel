<?php

namespace App\Filament\Admin\Resources\FinancialResource\Pages;

use App\Filament\Admin\Resources\FinancialResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFinancial extends EditRecord
{
    protected static string $resource = FinancialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 