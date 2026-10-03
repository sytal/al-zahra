<?php

namespace App\Filament\Resources\Courses\Pages\Concerns;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;

/**
 * Shared record-resolution for the course review sub-pages (ReviewAssignments,
 * CourseDiscussions) — thin wrapper so both pages authorize/resolve the
 * owning Course the same way EditCourse does.
 */
trait InteractsWithCourseRecord
{
    use InteractsWithRecord;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);

        $this->authorizeAccess();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);
    }

    public function hydrate(): void
    {
        $this->authorizeAccess();
    }

    public function getBreadcrumb(): string
    {
        return $this->getTitle();
    }
}
