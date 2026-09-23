<?php

namespace App\Filament\Resources\ResearchPapers\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\ResearchPapers\ResearchPaperResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditResearchPaper extends EditRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ResearchPaperResource::class;

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
            'paper_file' => 'paper_file',
            'cover_image' => 'cover_image',
        ]);
    }
}
