<?php

namespace Database\Seeders;

use App\Modules\Article\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Bilingualism', 'Early Childhood', 'Brain Development', 'Vocabulary',
            'Reading', 'Speech Therapy', 'Second Language', 'Cognitive Science',
            'Parenting Tips', 'Classroom Strategies',
        ];

        $tr = [
            'Bilingualism' => ['ur' => 'دو لسانیت', 'hi' => 'द्विभाषिकता', 'fa' => 'دوزبانگی', 'ur-roman' => 'Do lisaniyat'],
            'Early Childhood' => ['ur' => 'ابتدائی بچپن', 'hi' => 'प्रारंभिक बचपन', 'fa' => 'اوان کودکی', 'ur-roman' => 'Ibtidai bachpan'],
            'Brain Development' => ['ur' => 'دماغی نشوونما', 'hi' => 'मस्तिष्क विकास', 'fa' => 'رشد مغز', 'ur-roman' => 'Dimaghi nashonuma'],
            'Vocabulary' => ['ur' => 'ذخیرۂ الفاظ', 'hi' => 'शब्दावली', 'fa' => 'واژگان', 'ur-roman' => 'Zakhira-e-alfaz'],
            'Reading' => ['ur' => 'مطالعہ', 'hi' => 'पठन', 'fa' => 'خواندن', 'ur-roman' => 'Mutalia'],
            'Speech Therapy' => ['ur' => 'گفتار تھراپی', 'hi' => 'वाक् चिकित्सा', 'fa' => 'گفتاردرمانی', 'ur-roman' => 'Guftar therapy'],
            'Second Language' => ['ur' => 'دوسری زبان', 'hi' => 'दूसरी भाषा', 'fa' => 'زبان دوم', 'ur-roman' => 'Doosri zaban'],
            'Cognitive Science' => ['ur' => 'علمِ ادراک', 'hi' => 'संज्ञानात्मक विज्ञान', 'fa' => 'علوم شناختی', 'ur-roman' => 'Ilm-e-idrak'],
            'Parenting Tips' => ['ur' => 'والدین کے لیے مشورے', 'hi' => 'पालन-पोषण के सुझाव', 'fa' => 'نکات فرزندپروری', 'ur-roman' => 'Walidain ke liye mashwaray'],
            'Classroom Strategies' => ['ur' => 'کلاس روم حکمتِ عملی', 'hi' => 'कक्षा रणनीतियाँ', 'fa' => 'راهکارهای کلاسی', 'ur-roman' => 'Classroom hikmat-e-amli'],
        ];

        foreach ($tags as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => ['en' => $name] + ($tr[$name] ?? [])]
            );
        }
    }
}
