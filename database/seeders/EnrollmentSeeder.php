<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Services\CourseService;
use App\Support\Enums\EnrollmentStatus;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'student@alzahra.institute')->first();

        if (! $student) {
            return;
        }

        $service = app(CourseService::class);
        $courses = Course::orderBy('created_at')->orderBy('id')->take(2)->get();
        $marked = false;

        foreach ($courses as $course) {
            $enrollment = $service->enroll($student, $course);

            // CourseSeeder's seedDemoCompletion() may have already enrolled
            // and completed the student in one of these courses -- never
            // touch an already-completed enrollment's progress, and only
            // mark one course's first block in-progress for the demo.
            if ($marked || $enrollment->status === EnrollmentStatus::COMPLETED || $enrollment->blockProgress()->exists()) {
                continue;
            }

            $block = $course->modules()->orderBy('sort_order')->first()?->blocks()->orderBy('sort_order')->first();

            if (! $block) {
                continue;
            }

            CourseBlockProgress::firstOrCreate(
                ['enrollment_id' => $enrollment->id, 'course_block_id' => $block->id],
                ['status' => 'done', 'completed_at' => now()]
            );

            $totalBlocks = $course->modules()->withCount('blocks')->get()->sum('blocks_count');
            $enrollment->progress_percent = $totalBlocks > 0 ? (int) round(1 / $totalBlocks * 100) : 0;
            $enrollment->save();
            $marked = true;
        }
    }
}
