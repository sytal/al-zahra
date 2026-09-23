<?php

namespace App\Filament\Resources\Resources\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Resources\ResourceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditResource extends EditRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ResourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->syncMediaLibraryUploads([
            'resource_file' => 'resource_file',
            'thumbnail' => 'thumbnail',
        ]);
    }
}
