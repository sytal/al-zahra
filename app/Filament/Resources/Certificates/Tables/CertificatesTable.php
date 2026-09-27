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
                    ->label(__('admin_ui.l.verification_code'))
                    ->searchable()
                    ->copyable(),
                TextColumn::make('user.name')
                    ->label(__('admin_ui.l.user'))
                    ->searchable(),
                TextColumn::make('course.title')
                    ->label(__('admin_ui.l.course')),
                TextColumn::make('issued_at')
                    ->label(__('admin_ui.l.issued_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('issued_at', 'desc')
            ->recordActions([
                Action::make('revoke')
                    ->label(__('admin_ui.l.revoke'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->trashed())
                    ->action(function ($record) {
                        $record->delete();

                        Notification::make()
                            ->title(__('admin_ui.l.certificate_revoked'))
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
