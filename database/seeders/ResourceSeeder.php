<?php

namespace Database\Seeders;

use App\Modules\Category\Models\Category;
use App\Modules\Resource\Models\Resource;
use App\Support\Enums\CategoryType;
use App\Support\Enums\ResourceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', CategoryType::RESOURCE)->get();

        if ($categories->isEmpty()) {
            return;
        }

        $items = [
            ['title' => 'Bilingual Vocabulary Tracker Worksheet', 'type' => ResourceType::TEMPLATE, 'free' => true],
            ['title' => 'Early Language Milestones Checklist', 'type' => ResourceType::GUIDE, 'free' => true],
            ['title' => 'Speech Development Screening Questionnaire', 'type' => ResourceType::QUESTIONNAIRE, 'free' => true],
            ['title' => 'Classroom Bilingual Activity Pack', 'type' => ResourceType::PDF, 'free' => true],
            ['title' => 'Parent Guide to Supporting Two Languages at Home', 'type' => ResourceType::GUIDE, 'free' => true],
            ['title' => 'Reading Readiness Assessment Tool', 'type' => ResourceType::QUESTIONNAIRE, 'free' => true],
            ['title' => 'Advanced Language Therapy Planner', 'type' => ResourceType::TEMPLATE, 'free' => false],
            ['title' => 'Complete Neurolinguistics Reference Pack', 'type' => ResourceType::PDF, 'free' => false],
        ];

        $translations = [
            ['ur' => 'دو لسانی ذخیرہ الفاظ کی نگرانی کا ورک شیٹ', 'hi' => 'द्विभाषी शब्दावली ट्रैकर वर्कशीट', 'fa' => 'برگه کار پیگیری دایره واژگان دوزبانه', 'ur-roman' => 'Do lisani zakheera-e-alfaz ki nigrani ka worksheet'],
            ['ur' => 'ابتدائی زبان کے سنگ میلوں کی چیک لسٹ', 'hi' => 'प्रारंभिक भाषा पड़ावों की जाँच-सूची', 'fa' => 'فهرست بررسی نقاط عطف اولیه زبان', 'ur-roman' => 'Ibtidai zaban ke sang-e-meel ki check list'],
            ['ur' => 'گفتار کی نشوونما کی ابتدائی جانچ کا سوالنامہ', 'hi' => 'वाक् विकास स्क्रीनिंग प्रश्नावली', 'fa' => 'پرسش‌نامه غربالگری رشد گفتار', 'ur-roman' => 'Guftar ki nashonuma ki ibtidai jaanch ka sawalnama'],
            ['ur' => 'کلاس روم کے لیے دو لسانی سرگرمیوں کا پیک', 'hi' => 'कक्षा के लिए द्विभाषी गतिविधि पैक', 'fa' => 'بسته فعالیت‌های دوزبانه برای کلاس', 'ur-roman' => 'Class room ke liye do lisani sargarmiyon ka pack'],
            ['ur' => 'گھر پر دو زبانوں کی معاونت کے لیے والدین کی رہنما کتاب', 'hi' => 'घर पर दो भाषाओं को सहारा देने के लिए अभिभावक मार्गदर्शिका', 'fa' => 'راهنمای والدین برای حمایت از دو زبان در خانه', 'ur-roman' => 'Ghar par do zabanon ki moawinat ke liye walidain ki rehnuma kitab'],
            ['ur' => 'پڑھنے کی تیاری کی جانچ کا آلہ', 'hi' => 'पढ़ने की तत्परता आकलन उपकरण', 'fa' => 'ابزار ارزیابی آمادگی خواندن', 'ur-roman' => 'Parhne ki tayyari ki jaanch ka aala'],
            ['ur' => 'اعلیٰ زبانی علاج کا منصوبہ ساز', 'hi' => 'उन्नत भाषा चिकित्सा योजनाकार', 'fa' => 'برنامه‌ریز پیشرفته گفتاردرمانی', 'ur-roman' => 'Aala zabani ilaj ka mansooba saz'],
            ['ur' => 'اعصابی لسانیات کا مکمل حوالہ جاتی پیک', 'hi' => 'तंत्रिका भाषाविज्ञान का संपूर्ण संदर्भ पैक', 'fa' => 'بسته کامل مرجع زبان‌شناسی عصبی', 'ur-roman' => 'Asabi lisaniyat ka mukammal hawala jati pack'],
        ];

        $suffix = [
            'ur' => 'زبان کی نشوونما میں معاونت کے لیے ایک عملی، فوری استعمال کے قابل وسیلہ۔',
            'hi' => 'भाषा विकास में सहयोग के लिए एक व्यावहारिक, तुरंत उपयोग योग्य संसाधन।',
            'fa' => 'منبعی کاربردی و آماده استفاده برای حمایت از رشد زبان.',
            'ur-roman' => 'Zaban ki nashonuma mein moawinat ke liye aik amli, fauran istemal ke qabil wasila.',
        ];

        foreach ($items as $i => $item) {
            $slug = Str::slug($item['title']);

            $descriptionLocales = ['en' => $item['title'].' — a practical, ready-to-use resource for supporting language development.'];
            foreach ($translations[$i] as $locale => $translated) {
                $descriptionLocales[$locale] = $translated.': '.$suffix[$locale];
            }

            Resource::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => ['en' => $item['title']] + $translations[$i],
                    'description' => $descriptionLocales,
                    'resource_type' => $item['type'],
                    'is_free' => $item['free'],
                    'price' => $item['free'] ? null : 9.99,
                    'is_published' => true,
                ]
            );
        }
    }
}
