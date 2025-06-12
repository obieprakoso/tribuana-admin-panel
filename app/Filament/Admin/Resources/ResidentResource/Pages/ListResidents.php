<?php

namespace App\Filament\Admin\Resources\ResidentResource\Pages;

use App\Filament\Admin\Resources\ResidentResource;
use App\Enums\ResidentStatus;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class ListResidents extends ListRecords
{
    protected static string $resource = ResidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('head_of_family')
                    ->label('Head of Family')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('house_number')
                    ->label('House Number')
                    ->sortable(),
                
                TextColumn::make('contact')
                    ->label('Contact Number')
                    ->searchable(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (ResidentStatus $state): string => $state->value)
                    ->color(fn (ResidentStatus $state): string => match ($state) {
                        ResidentStatus::PERMANENT => 'success',
                        ResidentStatus::TEMPORARY => 'warning',
                    }),
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        ResidentStatus::PERMANENT->value => 'Permanent',
                        ResidentStatus::TEMPORARY->value => 'Temporary',
                    ])
                    ->label('Status'),
            ])
            ->defaultSort('created_at', 'desc');
    }
} 