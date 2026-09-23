<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = CourseResource::class;

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
            'cover_image' => 'cover_image',
        ]);
    }
}
