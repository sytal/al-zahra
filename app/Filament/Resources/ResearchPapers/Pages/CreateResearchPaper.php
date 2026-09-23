<?php

namespace App\Filament\Resources\ResearchPapers\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\ResearchPapers\ResearchPaperResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResearchPaper extends CreateRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ResearchPaperResource::class;

    protected function afterCreate(): void
    {
        $this->syncMediaLibraryUploads([
            'paper_file' => 'paper_file',
            'cover_image' => 'cover_image',
        ]);
    }
}
