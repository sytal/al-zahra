<?php

namespace App\Filament\Resources\SupportTickets\Pages;

use App\Filament\Resources\SupportTickets\SupportTicketResource;
use App\Modules\Support\Notifications\SupportTicketReplied;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class EditSupportTicket extends EditRecord
{
    protected static string $resource = SupportTicketResource::class;

    public ?string $reply = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendReply')
                ->label(__('admin_ui.l.send_reply'))
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('success')
                ->schema([
                    \Filament\Forms\Components\Textarea::make('reply')
                        ->label(__('admin_ui.l.reply'))
                        ->rows(4)
                        ->required(),
                ])
                ->requiresConfirmation()
                ->modalDescription(__('admin_ui.l.this_will_save_the_reply_and_mark_the_tic'))
                ->action(function (array $data) {
                    $this->record->messages()->create([
                        'author_id' => Auth::id(),
                        'body' => $data['reply'],
                    ]);

                    $this->record->update(['status' => 'answered']);

                    NotificationFacade::send($this->record->user, new SupportTicketReplied($this->record, repliedByStaff: true));

                    Notification::make()
                        ->title(__('admin_ui.l.reply_sent'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
