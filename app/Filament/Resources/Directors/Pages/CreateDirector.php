<?php

namespace App\Filament\Resources\Directors\Pages;

use App\Filament\Resources\Directors\DirectorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDirector extends CreateRecord
{
    protected static string $resource = DirectorResource::class;

    protected ?string $profilePhotoPath = null;

    protected ?string $coverPhotoPath = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->profilePhotoPath = $data['profile_photo'] ?? null;
        $this->coverPhotoPath = $data['cover_photo'] ?? null;

        unset($data['profile_photo'], $data['cover_photo']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->profilePhotoPath) {
            $this->record->addMediaFromDisk($this->profilePhotoPath, 'public_media')
                ->toMediaCollection('profile_photo');
        }

        if ($this->coverPhotoPath) {
            $this->record->addMediaFromDisk($this->coverPhotoPath, 'public_media')
                ->toMediaCollection('cover_photo');
        }
    }
}
