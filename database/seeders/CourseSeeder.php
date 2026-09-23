<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Support\Enums\CategoryType;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
use App\Support\Enums\LessonContentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::where('email', 'director@alzahra.institute')->first();
        $category = Category::where('type', CategoryType::COURSE)->first();

        if (! $instructor) {
            return;
        }

        $courses = [
            [
                'title' => 'Foundations of Bilingual Development',
                'audience' => CourseAudience::PARENTS,
                'level' => CourseLevel::BEGINNER,
            ],
            [
                'title' => 'Neurolinguistics for Educators',
                'audience' => CourseAudience::TEACHERS,
                'level' => CourseLevel::INTERMEDIATE,
            ],
            [
                'title' => 'Advanced Language Assessment Techniques',
                'audience' => CourseAudience::PROFESSIONALS,
                'level' => CourseLevel::ADVANCED,
            ],
        ];

        foreach ($courses as $data) {
            $slug = Str::slug($data['title']);

            $course = Course::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category?->id,
                    'instructor_id' => $instructor->id,
                    'title' => ['en' => $data['title']],
                    'short_description' => ['en' => $data['title'].' — a practical, evidence-based course.'],
                    'full_description' => ['en' => '<p>Full course description pending director review.</p>'],
                    'level' => $data['level'],
                    'audience' => $data['audience'],
                    'learning_outcomes' => ['en' => ['Understand core concepts', 'Apply techniques in real settings', 'Evaluate outcomes critically']],
                    'estimated_duration_hours' => 6,
                    'is_free' => true,
                    'is_published' => true,
                ]
            );

            if ($course->lessons()->count() === 0) {
                for ($i = 1; $i <= 5; $i++) {
                    $course->lessons()->create([
                        'title' => ['en' => "Lesson {$i}: ".$data['title']],
                        'content_type' => LessonContentType::TEXT,
                        'body' => ['en' => '<p>Lesson content placeholder.</p>'],
                        'duration_minutes' => 15,
                        'is_preview' => $i === 1,
                        'sort_order' => $i,
                    ]);
                }
            }
        }
    }
}
