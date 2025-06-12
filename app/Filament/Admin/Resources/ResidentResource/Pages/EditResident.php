<?php

namespace App\Filament\Admin\Resources\ResidentResource\Pages;

use App\Filament\Admin\Resources\ResidentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;

class EditResident extends EditRecord
{
    protected static string $resource = ResidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('head_of_family')
                    ->label('Head of Family')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('house_number')
                    ->label('House Number')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(17),
                
                TextInput::make('contact')
                    ->label('Contact Number')
                    ->tel()
                    ->required()
                    ->regex('/^(\+62|62|0)8[1-9][0-9]{6,9}$/')
                    ->helperText('Format: 08xxxxxxxxxx atau +628xxxxxxxxxx')
                    ->maxLength(15),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Temporary' => 'Temporary',
                    ])
                    ->required(),
            ]);
    }
}
