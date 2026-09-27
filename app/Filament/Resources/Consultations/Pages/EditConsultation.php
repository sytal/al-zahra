<?php

namespace App\Filament\Resources\Consultations\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Modules\Consultation\Jobs\SendConsultationAnsweredEmailJob;
use App\Support\Enums\ConsultationStatus;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditConsultation extends EditRecord
{
    protected static string $resource = ConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendAnswer')
                ->label(__('admin_ui.l.send_answer'))
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription(__('admin_ui.l.this_will_save_the_answer_mark_the_consu'))
                ->action(function () {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

                    $this->record->update([
                        'status' => ConsultationStatus::ANSWERED,
                        'answered_at' => now(),
                    ]);

                    SendConsultationAnsweredEmailJob::dispatch($this->record->fresh());

                    Notification::make()
                        ->title(__('admin_ui.l.answer_sent'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
