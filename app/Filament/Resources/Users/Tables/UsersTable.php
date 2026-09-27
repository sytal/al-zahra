<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Support\AdminEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Password;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label(__('admin_ui.l.avatar'))
                    ->circular()
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('avatar') ?: null)
                    ->defaultImageUrl(asset('images/avatar-placeholder.svg')),
                TextColumn::make('name')
                    ->label(__('admin_ui.l.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('admin_ui.l.email'))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('roles.name')
                    ->label(__('admin_ui.l.role'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => AdminEnum::label($state))
                    ->color('primary'),
                IconColumn::make('is_active')
                    ->label(__('admin_ui.l.active'))
                    ->boolean(),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('role')
                    ->label(__('admin_ui.l.role'))
                    ->relationship('roles', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => AdminEnum::label($record->name)),
                TernaryFilter::make('is_active')
                    ->label(__('admin_ui.l.active')),
            ])
            ->recordActions([
                Action::make('sendPasswordReset')
                    ->label(__('admin_ui.l.send_password_reset'))
                    ->icon('heroicon-o-key')
                    ->requiresConfirmation()
                    ->action(function ($record): void {
                        Password::sendResetLink(['email' => $record->email]);

                        Notification::make()
                            ->title(__('admin_ui.l.password_reset_email_sent'))
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
