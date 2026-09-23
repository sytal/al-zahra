<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('verification_code')
                    ->label('Verification Code')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('course.title')
                    ->label('Course'),
                TextColumn::make('issued_at')
                    ->label('Issued At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('issued_at', 'desc')
            ->recordActions([
                Action::make('revoke')
                    ->label('Revoke')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->trashed())
                    ->action(function ($record) {
                        $record->delete();

                        Notification::make()
                            ->title('Certificate revoked')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
