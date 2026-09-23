<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ArticleResource::class;

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
            'featured_image' => 'featured_image',
        ]);
    }
}
