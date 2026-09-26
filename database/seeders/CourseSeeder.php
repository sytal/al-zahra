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
            ],
            [
                'title' => 'Neurolinguistics for Educators',
                'tr' => ['ur' => 'اساتذہ کے لیے اعصابی لسانیات', 'hi' => 'शिक्षकों के लिए तंत्रिका भाषाविज्ञान', 'fa' => 'زبان‌شناسی عصبی برای معلمان', 'ur-roman' => 'Asatiza ke liye asabi lisaniyat'],
                'audience' => CourseAudience::TEACHERS,
                'level' => CourseLevel::INTERMEDIATE,
            ],
            [
                'title' => 'Advanced Language Assessment Techniques',
                'tr' => ['ur' => 'زبان کی جانچ کی اعلیٰ تکنیکیں', 'hi' => 'भाषा मूल्यांकन की उन्नत तकनीकें', 'fa' => 'فنون پیشرفته ارزیابی زبان', 'ur-roman' => 'Zaban ki jaanch ki aala techniquein'],
                'audience' => CourseAudience::PROFESSIONALS,
                'level' => CourseLevel::ADVANCED,
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
        $lessonWord = ['ur' => 'سبق', 'hi' => 'पाठ', 'fa' => 'درس', 'ur-roman' => 'Sabaq'];
        $lessonBody = [
            'ur' => '<p>سبق کا مواد جلد شامل کیا جائے گا۔</p>',
            'hi' => '<p>पाठ की सामग्री जल्द जोड़ी जाएगी।</p>',
            'fa' => '<p>محتوای درس به‌زودی افزوده می‌شود.</p>',
            'ur-roman' => '<p>Sabaq ka mawad jald shamil kiya jayega.</p>',
        ];

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
                    'is_free' => true,
                    'is_published' => true,
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
        }
    }
}
