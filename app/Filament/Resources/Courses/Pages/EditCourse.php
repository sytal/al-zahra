<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Concerns\SavesMediaLibraryUploads;
use App\Filament\Resources\Courses\CourseResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditCourse extends EditRecord
{
    use SavesMediaLibraryUploads;

    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reviewAssignments')
                ->label(__('admin_ui.l.review_assignments'))
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('gray')
                ->url(fn () => CourseResource::getUrl('review-assignments', ['record' => $this->record])),

            Action::make('courseDiscussions')
                ->label(__('admin_ui.l.course_discussions'))
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('gray')
                ->url(fn () => CourseResource::getUrl('course-discussions', ['record' => $this->record])),

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
