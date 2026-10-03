<?php

namespace App\Modules\Course\Services;

use App\Modules\Course\Events\CourseCompleted;
use App\Modules\Course\Models\CourseBlock;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Models\Enrollment;
use App\Support\Enums\EnrollmentStatus;

/**
 * Final submission lock (docs/COURSE-BUILDER-DEV-PLAN.md Phase 5): once the
 * last required block in a course is marked done, computes the enrollment's
 * final_score, stamps completed_at, and fires CourseCompleted (consumed by
 * the existing IssueCertificateListener — certificate issuance itself is
 * NOT duplicated here).
 */
class CourseCompletionService
{
    /**
     * Graded-quiz percentages and assignment pass(=100)/needs-revision(=0)
     * outcomes are weighted equally and averaged — the simplest honest
     * formula, not specified in the user plan so documented here as a
     * judgment call. Ungraded blocks (reading, discussion, etc.) do not
     * contribute a score.
     */
    public function finalize(Enrollment $enrollment): Enrollment
    {
        if ($enrollment->status === EnrollmentStatus::COMPLETED) {
            return $enrollment;
        }

        $moduleIds = $enrollment->course->modules()->pluck('id');
        $blocks = CourseBlock::whereIn('course_module_id', $moduleIds)->get();
        $progressByBlockId = CourseBlockProgress::where('enrollment_id', $enrollment->id)
            ->whereIn('course_block_id', $blocks->pluck('id'))
            ->get()
            ->keyBy('course_block_id');

        $scores = [];

        foreach ($blocks as $block) {
            $progress = $progressByBlockId->get($block->id);

            if ($block->type === 'graded_quiz') {
                $score = $progress?->data['score'] ?? null;

                if ($score !== null) {
                    $scores[] = (float) $score;
                }
            } elseif ($block->type === 'assignment') {
                $mark = $progress?->data['mark'] ?? null;

                if ($mark === 'pass') {
                    $scores[] = 100.0;
                } elseif ($mark === 'revision') {
                    $scores[] = 0.0;
                }
            }
        }

        $enrollment->final_score = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : null;
        $enrollment->status = EnrollmentStatus::COMPLETED;
        $enrollment->completed_at = now();
        $enrollment->save();

        CourseCompleted::dispatch($enrollment);

        return $enrollment;
    }
}
