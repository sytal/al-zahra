<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = CourseResource::class;

    protected function afterCreate(): void
    {
        $this->syncMediaLibraryUploads([
            'cover_image' => 'cover_image',
        ]);
    }
}
