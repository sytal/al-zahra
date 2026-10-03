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
                'tr' => ['ur' => 'دو لسانی نشوونما کی بنیادیں', 'hi' => 'द्विभाषी विकास की बुनियाद', 'fa' => 'مبانی رشد دوزبانگی', 'ur-roman' => 'Do lisani nashonuma ki bunyadein'],
                'audience' => CourseAudience::PARENTS,
                'level' => CourseLevel::BEGINNER,
                // Module/block mix 1: reading, discussion, practical_quiz, case_study, assignment, research_activity
                'modules' => [
                    ['title' => 'Getting Started', 'blocks' => ['reading', 'discussion']],
                    ['title' => 'Applying the Ideas', 'blocks' => ['practical_quiz', 'case_study']],
                    ['title' => 'Putting It Into Practice', 'blocks' => ['assignment', 'research_activity']],
                ],
            ],
            [
                'title' => 'Neurolinguistics for Educators',
                'tr' => ['ur' => 'اساتذہ کے لیے اعصابی لسانیات', 'hi' => 'शिक्षकों के लिए तंत्रिका भाषाविज्ञान', 'fa' => 'زبان‌شناسی عصبی برای معلمان', 'ur-roman' => 'Asatiza ke liye asabi lisaniyat'],
                'audience' => CourseAudience::TEACHERS,
                'level' => CourseLevel::INTERMEDIATE,
                // Module/block mix 2 (deliberately different set): research_reading, graded_quiz, discussion, case_analysis, research_activity, assignment
                'modules' => [
                    ['title' => 'The Research Base', 'blocks' => ['research_reading']],
                    ['title' => 'Checking Understanding', 'blocks' => ['graded_quiz', 'discussion']],
                    ['title' => 'Classroom Scenarios', 'blocks' => ['case_analysis']],
                    ['title' => 'Independent Work', 'blocks' => ['research_activity', 'assignment']],
                ],
            ],
            [
                'title' => 'Advanced Language Assessment Techniques',
                'tr' => ['ur' => 'زبان کی جانچ کی اعلیٰ تکنیکیں', 'hi' => 'भाषा मूल्यांकन की उन्नत तकनीकें', 'fa' => 'فنون پیشرفته ارزیابی زبان', 'ur-roman' => 'Zaban ki jaanch ki aala techniquein'],
                'audience' => CourseAudience::PROFESSIONALS,
                'level' => CourseLevel::ADVANCED,
                'is_free' => false,
                'price' => 49.00,
                // Module/block mix 3 (deliberately different set): reading, graded_quiz, research_paper, practical_quiz, discussion
                'modules' => [
                    ['title' => 'Assessment Theory', 'blocks' => ['reading', 'graded_quiz']],
                    ['title' => 'Research Literature', 'blocks' => ['research_paper']],
                    ['title' => 'Live Practice', 'blocks' => ['practical_quiz', 'discussion']],
                ],
                'batch' => true,
            ],
        ];

        $short = [
            'ur' => 'ایک عملی، ثبوت پر مبنی کورس۔',
            'hi' => 'एक व्यावहारिक, प्रमाण-आधारित पाठ्यक्रम।',
            'fa' => 'یک دوره کاربردی و مبتنی بر شواهد.',
            'ur-roman' => 'Aik amli, saboot par mabni course.',
        ];
        $full = [
            'ur' => '<p>کورس کی مکمل تفصیل ڈائریکٹر کے جائزے کے بعد شامل کی جائے گی۔</p>',
            'hi' => '<p>पाठ्यक्रम का पूरा विवरण निदेशक की समीक्षा के बाद जोड़ा जाएगा।</p>',
            'fa' => '<p>توضیحات کامل دوره پس از بازبینی مدیر افزوده خواهد شد.</p>',
            'ur-roman' => '<p>Course ki mukammal tafseel director ke jaize ke baad shamil ki jayegi.</p>',
        ];
        $outcomes = [
            'ur' => ['بنیادی تصورات کو سمجھنا', 'تکنیکوں کو حقیقی ماحول میں استعمال کرنا', 'نتائج کا تنقیدی جائزہ لینا'],
            'hi' => ['मूल अवधारणाओं को समझना', 'तकनीकों को वास्तविक परिस्थितियों में लागू करना', 'परिणामों का आलोचनात्मक मूल्यांकन करना'],
            'fa' => ['درک مفاهیم پایه', 'به‌کارگیری فنون در محیط‌های واقعی', 'ارزیابی انتقادی نتایج'],
            'ur-roman' => ['Bunyadi tasawwurat ko samajhna', 'Techniquon ko haqeeqi mahol mein istemal karna', 'Nataij ka tanqeedi jaiza lena'],
        ];
        $prerequisites = [
            'en' => ['Basic literacy in the language of instruction', 'Access to a computer with internet'],
        ];
        $courseResources = [
            ['label' => 'Reference glossary', 'kind' => 'note', 'value' => 'Key terms used throughout the course.'],
            ['label' => 'Institute resource library', 'kind' => 'link', 'value' => 'https://alzahra.institute/resources'],
        ];
        $lessonWord = ['ur' => 'سبق', 'hi' => 'पाठ', 'fa' => 'درس', 'ur-roman' => 'Sabaq'];
        $lessonBody = [
            'ur' => '<p>سبق کا مواد جلد شامل کیا جائے گا۔</p>',
            'hi' => '<p>पाठ की सामग्री जल्द जोड़ी जाएगी।</p>',
            'fa' => '<p>محتوای درس به‌زودی افزوده می‌شود.</p>',
            'ur-roman' => '<p>Sabaq ka mawad jald shamil kiya jayega.</p>',
        ];
        $moduleWord = ['ur' => 'ماڈیول', 'hi' => 'मॉड्यूल', 'fa' => 'ماژول', 'ur-roman' => 'Module'];
        $moduleDescWord = [
            'ur' => 'اس ماڈیول میں عملی مشقیں شامل ہیں۔',
            'hi' => 'इस मॉड्यूल में व्यावहारिक अभ्यास शामिल हैं।',
            'fa' => 'این ماژول شامل تمرین‌های کاربردی است.',
            'ur-roman' => 'Is module mein amli mashqain shamil hain.',
        ];
        $blockWord = ['ur' => 'بلاک', 'hi' => 'ब्लॉक', 'fa' => 'بلوک', 'ur-roman' => 'Block'];

        foreach ($courses as $data) {
            $slug = Str::slug($data['title']);
            $shortLocales = ['en' => $data['title'].' — a practical, evidence-based course.'];
            $fullLocales = ['en' => '<p>Full course description pending director review.</p>'];
            $outcomeLocales = ['en' => ['Understand core concepts', 'Apply techniques in real settings', 'Evaluate outcomes critically']];
            foreach ($data['tr'] as $locale => $translated) {
                $shortLocales[$locale] = $translated.': '.$short[$locale];
                $fullLocales[$locale] = $full[$locale];
                $outcomeLocales[$locale] = $outcomes[$locale];
            }

            $course = Course::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category?->id,
                    'instructor_id' => $instructor->id,
                    'title' => ['en' => $data['title']] + $data['tr'],
                    'short_description' => $shortLocales,
                    'full_description' => $fullLocales,
                    'level' => $data['level'],
                    'audience' => $data['audience'],
                    'learning_outcomes' => $outcomeLocales,
                    'estimated_duration_hours' => 6,
                    'is_free' => $data['is_free'] ?? true,
                    'price' => $data['price'] ?? null,
                    'is_published' => true,
                    'prerequisites' => $prerequisites['en'],
                    'course_resources' => $courseResources,
                ]
            );

            if ($course->lessons()->count() === 0) {
                for ($i = 1; $i <= 5; $i++) {
                    $lessonTitles = ['en' => "Lesson {$i}: ".$data['title']];
                    foreach ($data['tr'] as $locale => $translated) {
                        $lessonTitles[$locale] = "{$lessonWord[$locale]} {$i}: {$translated}";
                    }

                    $course->lessons()->create([
                        'title' => $lessonTitles,
                        'content_type' => LessonContentType::TEXT,
                        'body' => ['en' => '<p>Lesson content placeholder.</p>'] + $lessonBody,
                        'duration_minutes' => 15,
                        'is_preview' => $i === 1,
                        'sort_order' => $i,
                    ]);
                }
            }

            if ($course->modules()->count() === 0) {
                foreach ($data['modules'] as $moduleIndex => $moduleData) {
                    $moduleNumber = $moduleIndex + 1;
                    $moduleTitles = ['en' => "Module {$moduleNumber}: {$moduleData['title']}"];
                    $moduleDescriptions = ['en' => "This module covers {$moduleData['title']} through a mix of hands-on activities."];
                    foreach ($data['tr'] as $locale => $translated) {
                        $moduleTitles[$locale] = "{$moduleWord[$locale]} {$moduleNumber}: {$moduleData['title']}";
                        $moduleDescriptions[$locale] = $moduleDescWord[$locale];
                    }

                    $module = $course->modules()->create([
                        'title' => $moduleTitles,
                        'description' => $moduleDescriptions,
                        'sort_order' => $moduleNumber,
                    ]);

                    foreach ($moduleData['blocks'] as $blockIndex => $type) {
                        $blockNumber = $blockIndex + 1;
                        $blockTitles = ['en' => $this->blockLabel($type)];
                        foreach ($data['tr'] as $locale => $translated) {
                            $blockTitles[$locale] = "{$blockWord[$locale]} {$blockNumber}: ".$this->blockLabel($type);
                        }

                        $module->blocks()->create([
                            'type' => $type,
                            'title' => $blockTitles,
                            'is_preview' => $moduleIndex === 0 && $blockIndex === 0,
                            'sort_order' => $blockNumber,
                            'estimated_minutes' => 20,
                            'content' => $this->blockContent($type),
                        ]);
                    }
                }
            }

            if (($data['batch'] ?? false) && $course->batches()->count() === 0) {
                $course->batches()->create([
                    'label' => 'Batch 1',
                    'starts_at' => now()->addWeek()->toDateString(),
                    'ends_at' => now()->addWeeks(9)->toDateString(),
                    'seats' => 2,
                ]);
            }
        }
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

    private function blockContent(string $type): array
    {
        return match ($type) {
            'reading', 'research_reading' => [
                'body' => '<p>Reading content placeholder — to be completed by the course author.</p>',
            ],
            'practical_quiz' => [
                'questions' => [
                    [
                        'question' => 'Which strategy best supports the concept covered in this module?',
                        'options' => [
                            ['text' => 'Option A', 'correct' => true],
                            ['text' => 'Option B', 'correct' => false],
                        ],
                        'explanation' => 'Option A aligns with the evidence discussed in the reading.',
                    ],
                ],
            ],
            'graded_quiz' => [
                'questions' => [
                    [
                        'question' => 'Select the statement that best reflects best practice.',
                        'options' => [
                            ['text' => 'Statement A', 'correct' => true],
                            ['text' => 'Statement B', 'correct' => false],
                        ],
                        'explanation' => 'Statement A is supported by the module literature.',
                    ],
                ],
                'pass_percent' => 70,
            ],
            'case_study', 'research_paper', 'case_analysis' => [
                'body' => '<p>Case write-up placeholder — to be completed by the course author.</p>',
                'links' => [
                    ['url' => 'https://alzahra.institute/resources', 'reference' => 'Al Zahra Institute Resource Library'],
                ],
            ],
            'discussion' => [
                'prompt' => 'Share your reflections on this topic and respond to at least one peer.',
            ],
            'assignment' => [
                'instructions' => '<p>Complete the task described and submit your work for review.</p>',
                'files' => [],
                'due_date' => null,
            ],
            'research_activity' => [
                'prompt' => 'Investigate a real-world example and summarize your findings.',
                'requires_submission' => true,
            ],
            default => [],
        };
    }
}
