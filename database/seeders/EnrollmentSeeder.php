<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Models\CourseLesson;
use App\Modules\Course\Services\CourseService;
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

        foreach ($courses as $index => $course) {
            $service->enroll($student, $course);

            if ($index === 0) {
                $lesson = CourseLesson::where('course_id', $course->id)->orderBy('sort_order')->first();

                if ($lesson) {
                    $service->markLessonComplete($student, $lesson);
                }
            }
        }
    }
}
