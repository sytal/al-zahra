<?php

namespace App\Modules\Course\Services;

use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Notifications\AssignmentMarked;
use Illuminate\Support\Facades\Notification;

/**
 * Admin review of student assignment submissions
 * (docs/COURSE-BUILDER-DEV-PLAN.md Phase 7/8 — Filament "Review assignments"
 * page).
 */
class AssignmentReviewService
{
    /**
     * @param  'pass'|'needs_revision'  $mark
     */
    public function mark(CourseBlockProgress $progress, string $mark, ?string $comment = null): CourseBlockProgress
    {
        $data = $progress->data ?? [];
        $data['mark'] = $mark;
        $data['comment'] = $comment;
        $progress->data = $data;
        $progress->status = 'done';
        $progress->completed_at = now();
        $progress->save();

        $student = $progress->enrollment?->user;

        if ($student) {
            Notification::send($student, new AssignmentMarked($progress, $mark));
        }

        return $progress;
    }
}
