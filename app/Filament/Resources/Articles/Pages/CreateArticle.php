<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
    {
        $this->syncMediaLibraryUploads([
            'featured_image' => 'featured_image',
        ]);
    }
}
