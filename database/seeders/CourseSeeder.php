<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Models\Course;
use App\Support\Enums\CategoryType;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
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

        $courses = $this->loadCourseData();
        $titles = require base_path('database/seeders/data/course_titles_tr.php');

        // One designated course keeps a small open batch and another a
        // batch already filled at seed time, so the batch/waitlist paths
        // (docs/COURSE-BUILDER-PLAN.md Part C point 5) stay observable
        // right after seeding without manual setup. Every course is free
        // per the standing "no paid courses for now" instruction.
        $batchSlugs = [
            Str::slug($courses[2]['title']) => ['seats' => 2],
            Str::slug($courses[9]['title']) => ['seats' => 1, 'fill' => true],
        ];

        foreach ($courses as $index => $data) {
            $slug = Str::slug($data['title']);
            $tr = $titles[$data['title']] ?? [];

            $titleLocales = ['en' => $data['title']] + $tr;
            $shortLocales = ['en' => $data['short_desc']] + $this->translatedShortDesc($tr);
            $fullLocales = ['en' => '<p>'.$data['short_desc'].'</p>'] + $this->translatedFullDesc($tr);
            $outcomeLocales = ['en' => $data['outcomes']] + $this->translatedOutcomes($tr);

            $course = Course::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category?->id,
                    'instructor_id' => $instructor->id,
                    'title' => $titleLocales,
                    'short_description' => $shortLocales,
                    'full_description' => $fullLocales,
                    'level' => CourseLevel::from($data['level']),
                    'audience' => CourseAudience::from($data['audience']),
                    'learning_outcomes' => $outcomeLocales,
                    'estimated_duration_hours' => 6,
                    'is_free' => true,
                    'price' => null,
                    'is_published' => true,
                    'prerequisites' => ['Basic literacy in the language of instruction', 'Access to a computer with internet'],
                    'course_resources' => [
                        ['label' => 'Reference glossary', 'kind' => 'note', 'value' => 'Key terms used throughout the course.'],
                        ['label' => 'Institute resource library', 'kind' => 'link', 'value' => 'https://alzahra.institute/resources'],
                    ],
                ]
            );

            if ($course->modules()->count() === 0) {
                $this->createModules($course, $data, $tr);
            }

            if (($batchSlugs[$slug] ?? null) && $course->batches()->count() === 0) {
                $this->createBatch($course, $batchSlugs[$slug]);
            }
        }

        $this->seedDemoCompletion($courses[0]['title']);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadCourseData(): array
    {
        $courses = [];
        for ($batch = 1; $batch <= 5; $batch++) {
            $courses = array_merge($courses, require base_path("database/seeders/data/courses_batch_{$batch}.php"));
        }

        return $courses;
    }

    private function createModules(Course $course, array $data, array $tr): void
    {
        $moduleWord = ['ur' => 'ماڈیول', 'hi' => 'मॉड्यूल', 'fa' => 'ماژول', 'ur-roman' => 'Module'];
        $blockWord = ['ur' => 'بلاک', 'hi' => 'ब्लॉक', 'fa' => 'بلوک', 'ur-roman' => 'Block'];

        foreach ($data['modules'] as $moduleIndex => $moduleData) {
            $moduleNumber = $moduleIndex + 1;
            $moduleTitles = ['en' => "Module {$moduleNumber}: {$moduleData['title']}"];
            $moduleDescriptions = ['en' => "This module covers {$moduleData['title']}."];
            foreach (array_keys($tr) as $locale) {
                $moduleTitles[$locale] = "{$moduleWord[$locale]} {$moduleNumber}: {$moduleData['title']}";
                $moduleDescriptions[$locale] = $moduleTitles[$locale];
            }

            $module = $course->modules()->create([
                'title' => $moduleTitles,
                'description' => $moduleDescriptions,
                'sort_order' => $moduleNumber,
            ]);

            foreach ($moduleData['blocks'] as $blockIndex => $blockData) {
                $blockNumber = $blockIndex + 1;
                $label = $this->blockLabel($blockData['type']);
                $blockTitles = ['en' => $label];
                foreach (array_keys($tr) as $locale) {
                    $blockTitles[$locale] = "{$blockWord[$locale]} {$blockNumber}: {$label}";
                }

                $module->blocks()->create([
                    'type' => $blockData['type'],
                    'title' => $blockTitles,
                    'is_preview' => $moduleIndex === 0 && $blockIndex === 0,
                    'sort_order' => $blockNumber,
                    'estimated_minutes' => 20,
                    'content' => $blockData['content'],
                ]);
            }
        }
    }

    private function createBatch(Course $course, array $batchConfig): void
    {
        $batch = $course->batches()->create([
            'label' => 'Batch 1',
            'starts_at' => now()->addWeek()->toDateString(),
            'ends_at' => now()->addWeeks(9)->toDateString(),
            'seats' => $batchConfig['seats'],
        ]);

        // Demo-only scenario: fill every seat so the waitlist path is
        // observable right after seeding, not just after manual testing.
        if ($batchConfig['fill'] ?? false) {
            for ($seat = 1; $seat <= $batchConfig['seats']; $seat++) {
                $fillerUser = User::factory()->create(['name' => "Demo Enrollee {$seat}"]);
                $enrollment = \App\Modules\Course\Models\Enrollment::create([
                    'user_id' => $fillerUser->id,
                    'course_id' => $course->id,
                    'status' => 'active',
                    'enrolled_at' => now(),
                    'course_batch_id' => $batch->id,
                ]);
                $batch->batchEnrollments()->create([
                    'enrollment_id' => $enrollment->id,
                    'status' => 'enrolled',
                    'roll_number' => 'B1-'.str_pad((string) $seat, 4, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }

    /**
     * Demo-only: give the seeded student account one already-completed
     * course with an issued certificate, so "view your certificate" is
     * observable immediately after seeding without manually finishing a
     * course first.
     */
    private function seedDemoCompletion(string $firstCourseTitle): void
    {
        $student = User::where('email', 'student@alzahra.institute')->first();
        $course = Course::where('slug', Str::slug($firstCourseTitle))->first();

        if (! $student || ! $course) {
            return;
        }

        $enrollment = \App\Modules\Course\Models\Enrollment::firstOrCreate(
            ['user_id' => $student->id, 'course_id' => $course->id],
            ['status' => 'active', 'enrolled_at' => now()->subWeek()]
        );

        if ($enrollment->status === \App\Support\Enums\EnrollmentStatus::COMPLETED) {
            return;
        }

        $blocks = $course->modules()->with('blocks')->get()->flatMap->blocks;
        foreach ($blocks as $block) {
            \App\Modules\Course\Models\CourseBlockProgress::firstOrCreate(
                ['enrollment_id' => $enrollment->id, 'course_block_id' => $block->id],
                ['status' => 'done', 'completed_at' => now()->subDay()]
            );
        }

        app(\App\Modules\Course\Services\CourseCompletionService::class)->finalize($enrollment->fresh());
    }

    private function translatedShortDesc(array $tr): array
    {
        $locales = [];
        foreach (array_keys($tr) as $locale) {
            $locales[$locale] = $tr[$locale];
        }

        return $locales;
    }

    private function translatedFullDesc(array $tr): array
    {
        $full = [
            'ur' => '<p>کورس کی مکمل تفصیل جلد شامل کی جائے گی۔</p>',
            'hi' => '<p>पाठ्यक्रम का पूरा विवरण जल्द जोड़ा जाएगा।</p>',
            'fa' => '<p>توضیحات کامل دوره به‌زودی افزوده خواهد شد.</p>',
            'ur-roman' => '<p>Course ki mukammal tafseel jald shamil ki jayegi.</p>',
        ];
        $locales = [];
        foreach (array_keys($tr) as $locale) {
            $locales[$locale] = $full[$locale] ?? $full['ur-roman'];
        }

        return $locales;
    }

    private function translatedOutcomes(array $tr): array
    {
        $outcomes = [
            'ur' => ['بنیادی تصورات کو سمجھنا', 'تکنیکوں کو حقیقی ماحول میں استعمال کرنا', 'نتائج کا تنقیدی جائزہ لینا'],
            'hi' => ['मूल अवधारणाओं को समझना', 'तकनीकों को वास्तविक परिस्थितियों में लागू करना', 'परिणामों का आलोचनात्मक मूल्यांकन करना'],
            'fa' => ['درک مفاهیم پایه', 'به‌کارگیری فنون در محیط‌های واقعی', 'ارزیابی انتقادی نتایج'],
            'ur-roman' => ['Bunyadi tasawwurat ko samajhna', 'Techniquon ko haqeeqi mahol mein istemal karna', 'Nataij ka tanqeedi jaiza lena'],
        ];
        $locales = [];
        foreach (array_keys($tr) as $locale) {
            $locales[$locale] = $outcomes[$locale] ?? $outcomes['ur-roman'];
        }

        return $locales;
    }

    private function blockLabel(string $type): string
    {
        return match ($type) {
            'reading' => 'Reading: Core Concepts',
            'research_reading' => 'Research Reading: Current Literature',
            'practical_quiz' => 'Practical Quiz',
            'graded_quiz' => 'Graded Quiz',
            'case_study' => 'Case Study',
            'research_paper' => 'Research Paper Review',
            'case_analysis' => 'Case Analysis',
            'discussion' => 'Discussion',
            'assignment' => 'Assignment',
            'research_activity' => 'Research Activity',
            default => Str::headline($type),
        };
    }
}
