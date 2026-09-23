<?php

namespace App\Filament\Resources\Directors\Pages;

use App\Filament\Resources\Directors\DirectorResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDirector extends EditRecord
{
    protected static string $resource = DirectorResource::class;

    protected ?string $profilePhotoPath = null;

    protected ?string $coverPhotoPath = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['profile_photo'] = null;
        $data['cover_photo'] = null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->profilePhotoPath = $data['profile_photo'] ?? null;
        $this->coverPhotoPath = $data['cover_photo'] ?? null;

        unset($data['profile_photo'], $data['cover_photo']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->profilePhotoPath) {
            $this->record->addMediaFromDisk($this->profilePhotoPath, config('filesystems.default'))
                ->toMediaCollection('profile_photo');
        }

        if ($this->coverPhotoPath) {
            $this->record->addMediaFromDisk($this->coverPhotoPath, config('filesystems.default'))
                ->toMediaCollection('cover_photo');
        }
    }
}
