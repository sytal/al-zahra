<?php

namespace App\Filament\Resources\Resources\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Resources\ResourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResource extends CreateRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ResourceResource::class;

    protected function afterCreate(): void
    {
        $this->syncMediaLibraryUploads([
            'resource_file' => 'resource_file',
            'thumbnail' => 'thumbnail',
        ]);
    }
}
