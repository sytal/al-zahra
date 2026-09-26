<?php

namespace Database\Seeders;

use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            CategoryType::ARTICLE->value => ['Language Development', 'Bilingual Minds', 'Learning Science', 'Parenting & Language'],
            CategoryType::RESEARCH->value => ['Neurolinguistics', 'Cognitive Development', 'Applied Linguistics', 'Speech & Language Disorders'],
            CategoryType::RESOURCE->value => ['Worksheets', 'Assessment Tools', 'Parent Guides', 'Classroom Activities'],
            CategoryType::COURSE->value => ['Foundations', 'Professional Development', 'Family Workshops', 'Advanced Topics'],
        ];

        $tr = [
            'Language Development' => ['ur' => 'زبان کی نشوونما', 'hi' => 'भाषा विकास', 'fa' => 'رشد زبان', 'ur-roman' => 'Zaban ki nashonuma'],
            'Bilingual Minds' => ['ur' => 'دو لسانی ذہن', 'hi' => 'द्विभाषी मस्तिष्क', 'fa' => 'ذهن\u200cهای دوزبانه', 'ur-roman' => 'Do lisani zehn'],
            'Learning Science' => ['ur' => 'علمِ تعلم', 'hi' => 'सीखने का विज्ञान', 'fa' => 'علم یادگیری', 'ur-roman' => 'Ilm-e-taallum'],
            'Parenting & Language' => ['ur' => 'والدین اور زبان', 'hi' => 'पालन-पोषण और भाषा', 'fa' => 'فرزندپروری و زبان', 'ur-roman' => 'Walidain aur zaban'],
            'Neurolinguistics' => ['ur' => 'عصبی لسانیات', 'hi' => 'तंत्रिका-भाषाविज्ञान', 'fa' => 'عصب\u200cزبان\u200cشناسی', 'ur-roman' => 'Neurolinguistics'],
            'Cognitive Development' => ['ur' => 'ادراکی نشوونما', 'hi' => 'संज्ञानात्मक विकास', 'fa' => 'رشد شناختی', 'ur-roman' => 'Idraki nashonuma'],
            'Applied Linguistics' => ['ur' => 'اطلاقی لسانیات', 'hi' => 'अनुप्रयुक्त भाषाविज्ञान', 'fa' => 'زبان\u200cشناسی کاربردی', 'ur-roman' => 'Itlaqi lisaniyat'],
            'Speech & Language Disorders' => ['ur' => 'گفتار اور زبان کے عوارض', 'hi' => 'वाक् और भाषा विकार', 'fa' => 'اختلالات گفتار و زبان', 'ur-roman' => 'Guftar aur zaban ke awarz'],
            'Worksheets' => ['ur' => 'ورک شیٹس', 'hi' => 'कार्यपत्रक', 'fa' => 'برگه\u200cهای کار', 'ur-roman' => 'Worksheets'],
            'Assessment Tools' => ['ur' => 'جانچ کے اوزار', 'hi' => 'मूल्यांकन उपकरण', 'fa' => 'ابزارهای ارزیابی', 'ur-roman' => 'Jaanch ke auzaar'],
            'Parent Guides' => ['ur' => 'والدین کے لیے رہنما', 'hi' => 'अभिभावक मार्गदर्शिकाएँ', 'fa' => 'راهنمای والدین', 'ur-roman' => 'Walidain ke liye rehnuma'],
            'Classroom Activities' => ['ur' => 'کلاس روم سرگرمیاں', 'hi' => 'कक्षा गतिविधियाँ', 'fa' => 'فعالیت\u200cهای کلاسی', 'ur-roman' => 'Classroom sargarmiyan'],
            'Foundations' => ['ur' => 'بنیادیں', 'hi' => 'बुनियादी बातें', 'fa' => 'مبانی', 'ur-roman' => 'Bunyadein'],
            'Professional Development' => ['ur' => 'پیشہ ورانہ ترقی', 'hi' => 'व्यावसायिक विकास', 'fa' => 'توسعه حرفه\u200cای', 'ur-roman' => 'Pesha waranah taraqqi'],
            'Family Workshops' => ['ur' => 'خاندانی ورکشاپس', 'hi' => 'पारिवारिक कार्यशालाएँ', 'fa' => 'کارگاه\u200cهای خانوادگی', 'ur-roman' => 'Khandani workshops'],
            'Advanced Topics' => ['ur' => 'اعلیٰ موضوعات', 'hi' => 'उन्नत विषय', 'fa' => 'موضوعات پیشرفته', 'ur-roman' => 'Aala mauzuaat'],
        ];

        foreach ($names as $type => $labels) {
            foreach ($labels as $label) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($type.'-'.$label)],
                    ['name' => ['en' => $label] + ($tr[$label] ?? []), 'type' => $type]
                );
            }
        }
    }
}
