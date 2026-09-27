<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendPasswordReset')
                ->label(__('admin_ui.l.send_password_reset'))
                ->icon('heroicon-o-key')
                ->requiresConfirmation()
                ->action(function (): void {
                    Password::sendResetLink(['email' => $this->record->email]);

                    Notification::make()
                        ->title(__('admin_ui.l.password_reset_email_sent'))
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
