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
                ->label('Send Answer')
                ->icon(Heroicon::OutlinedPaperAirplane)
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('This will save the answer, mark the consultation as answered, and email the requester.')
                ->action(function () {
                    $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

                    $this->record->update([
                        'status' => ConsultationStatus::ANSWERED,
                        'answered_at' => now(),
                    ]);

                    SendConsultationAnsweredEmailJob::dispatch($this->record->fresh());

                    Notification::make()
                        ->title('Answer sent')
                        ->success()
                        ->send();
                }),
        ];
    }
}
