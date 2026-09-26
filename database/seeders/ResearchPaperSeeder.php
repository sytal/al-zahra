<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Research\Models\ResearchPaper;
use App\Support\Enums\CategoryType;
use App\Support\Enums\FullPaperType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResearchPaperSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'director@alzahra.institute')->first();
        $categories = Category::where('type', CategoryType::RESEARCH)->get();

        if (! $author || $categories->isEmpty()) {
            return;
        }

        $titles = [
            'Cross-Linguistic Transfer in Early Bilingual Learners',
            'Neural Correlates of Code-Switching in Adult Bilinguals',
            'The Effect of Bilingualism on Executive Function Development',
            'Phonological Awareness as a Predictor of Reading Success',
            'Language Attrition in Heritage Speakers: A Longitudinal Study',
            'Working Memory and Second Language Vocabulary Acquisition',
        ];

        $translations = [
            ['ur' => ['ابتدائی دو لسانی سیکھنے والوں میں بین لسانی منتقلی', 'بین لسانی منتقلی'], 'hi' => ['प्रारंभिक द्विभाषी शिक्षार्थियों में अंतर-भाषाई स्थानांतरण', 'अंतर-भाषाई स्थानांतरण'], 'fa' => ['انتقال بین‌زبانی در زبان‌آموزان دوزبانه نخستین', 'انتقال بین‌زبانی'], 'ur-roman' => ['Ibtidai do lisani seekhne walon mein bain-al-lisani muntaqili', 'Bain-al-lisani muntaqili']],
            ['ur' => ['بالغ دو لسانی افراد میں کوڈ سوئچنگ کے اعصابی مظاہر', 'کوڈ سوئچنگ کے اعصابی مظاہر'], 'hi' => ['वयस्क द्विभाषियों में कोड-स्विचिंग के तंत्रिका संबंध', 'कोड-स्विचिंग के तंत्रिका संबंध'], 'fa' => ['همبسته‌های عصبی تغییر کد زبانی در بزرگسالان دوزبانه', 'همبسته‌های عصبی تغییر کد زبانی'], 'ur-roman' => ['Balig do lisani afrad mein code-switching ke asabi mazahir', 'Code-switching ke asabi mazahir']],
            ['ur' => ['دو لسانیت کا انتظامی افعال کی نشوونما پر اثر', 'دو لسانیت کے انتظامی افعال کی نشوونما'], 'hi' => ['द्विभाषिकता का कार्यकारी कार्यों के विकास पर प्रभाव', 'द्विभाषिकता के कार्यकारी कार्यों के विकास'], 'fa' => ['تأثیر دوزبانگی بر رشد کارکردهای اجرایی', 'تأثیر دوزبانگی بر رشد کارکردهای اجرایی'], 'ur-roman' => ['Do lisaniyat ka intizami afaal ki nashonuma par asar', 'Do lisaniyat ke intizami afaal ki nashonuma']],
            ['ur' => ['صوتیاتی آگاہی بطور پڑھنے کی کامیابی کا پیش گو', 'صوتیاتی آگاہی'], 'hi' => ['ध्वन्यात्मक जागरूकता: पढ़ने की सफलता का पूर्वसूचक', 'ध्वन्यात्मक जागरूकता'], 'fa' => ['آگاهی واج‌شناختی به‌عنوان پیش‌بینی‌کننده موفقیت در خواندن', 'آگاهی واج‌شناختی'], 'ur-roman' => ['Sotiyati aagahi bator parhne ki kamyabi ka peshgo', 'Sotiyati aagahi']],
            ['ur' => ['ورثے کی زبان بولنے والوں میں زبان کا زوال: ایک طویل مدتی مطالعہ', 'زبان کا زوال'], 'hi' => ['विरासत भाषा बोलने वालों में भाषा क्षरण: एक दीर्घकालिक अध्ययन', 'भाषा क्षरण'], 'fa' => ['فرسایش زبان در سخنگویان زبان میراثی: یک مطالعه طولی', 'فرسایش زبان'], 'ur-roman' => ['Wirse ki zaban bolne walon mein zaban ka zawal: aik tawil muddati mutalea', 'Zaban ka zawal']],
            ['ur' => ['ورکنگ میموری اور دوسری زبان کے ذخیرہ الفاظ کا حصول', 'ورکنگ میموری اور دوسری زبان کے ذخیرہ الفاظ کا حصول'], 'hi' => ['कार्यकारी स्मृति और दूसरी भाषा की शब्दावली का अर्जन', 'कार्यकारी स्मृति और दूसरी भाषा की शब्दावली का अर्जन'], 'fa' => ['حافظه کاری و فراگیری واژگان زبان دوم', 'حافظه کاری و فراگیری واژگان زبان دوم'], 'ur-roman' => ['Working memory aur doosri zaban ke zakheera-e-alfaz ka husool', 'Working memory aur doosri zaban ke zakheera-e-alfaz ka husool']],
        ];

        $question = [
            'ur' => '{t} کا زبان کی نشوونما میں کیا کردار ہے؟',
            'hi' => 'भाषा विकास में {t} की क्या भूमिका है?',
            'fa' => '{t} چه نقشی در رشد زبان دارد؟',
            'ur-roman' => '{t} ka zaban ki nashonuma mein kya kirdar hai?',
        ];

        $methodology = [
            'ur' => 'مخلوط طریقوں پر مبنی مطالعہ جس میں متنوع شرکاء کے نمونے پر رویّاتی جانچ اور طویل مدتی مشاہدہ یکجا کیا گیا۔',
            'hi' => 'मिश्रित-विधि अध्ययन, जिसमें विविध प्रतिभागी समूह पर व्यवहारिक परीक्षण और दीर्घकालिक अवलोकन को जोड़ा गया।',
            'fa' => 'مطالعه‌ای با روش‌های ترکیبی که آزمون رفتاری و مشاهده طولی را در نمونه‌ای متنوع از شرکت‌کنندگان به هم می‌آمیزد.',
            'ur-roman' => 'Mikhlot tareeqon par mabni mutalea jis mein mutanawwe shurka ke namune par rawaiyyati jaanch aur tawil muddati mushahida yakja kiya gaya.',
        ];

        $findings = [
            'ur' => 'نتائج ایک قابلِ پیمائش اور شماریاتی طور پر معنی خیز تعلق کی نشاندہی کرتے ہیں جو اس شعبے کی سابقہ تحقیق سے ہم آہنگ ہے۔',
            'hi' => 'निष्कर्ष एक मापने योग्य और सांख्यिकीय रूप से सार्थक संबंध का संकेत देते हैं, जो इस क्षेत्र के पूर्व शोध से मेल खाता है।',
            'fa' => 'یافته‌ها بر رابطه‌ای قابل‌اندازه‌گیری و از نظر آماری معنادار دلالت دارند که با پژوهش‌های پیشین این حوزه همخوان است.',
            'ur-roman' => 'Nataij aik qabil-e-paimaish aur shumariyati taur par maani khez talluq ki nishandehi karte hain jo is shobe ki sabiqa tehqeeq se hum-ahang hai.',
        ];

        $significance = [
            'ur' => 'ان نتائج کے دو لسانی آبادی کے ساتھ کام کرنے والے اساتذہ اور معالجین کے لیے براہِ راست مضمرات ہیں۔',
            'hi' => 'इन परिणामों का द्विभाषी आबादी के साथ काम करने वाले शिक्षकों और चिकित्सकों के लिए सीधा महत्व है।',
            'fa' => 'این نتایج پیامدهای مستقیمی برای معلمان و درمانگرانی دارد که با جمعیت‌های دوزبانه کار می‌کنند.',
            'ur-roman' => 'In nataij ke do lisani abadi ke sath kaam karne wale asatiza aur mualijin ke liye barah-e-rast mazmirat hain.',
        ];

        foreach ($titles as $i => $title) {
            $slug = Str::slug($title);
            $isExternal = $i % 2 === 0;

            $titleLocales = ['en' => $title];
            $questionLocales = ['en' => 'What role does '.strtolower(explode(' in ', $title)[0]).' play in language development?'];
            $methodLocales = ['en' => 'Mixed-methods study combining behavioral testing and longitudinal observation across a diverse participant sample.'];
            $findingsLocales = ['en' => 'Findings suggest a measurable, statistically significant relationship consistent with prior literature in the field.'];
            $significanceLocales = ['en' => 'These results have direct implications for educators and clinicians working with bilingual populations.'];
            foreach ($translations[$i] as $locale => [$translatedTitle, $topic]) {
                $titleLocales[$locale] = $translatedTitle;
                $questionLocales[$locale] = str_replace('{t}', $topic, $question[$locale]);
                $methodLocales[$locale] = $methodology[$locale];
                $findingsLocales[$locale] = $findings[$locale];
                $significanceLocales[$locale] = $significance[$locale];
            }

            ResearchPaper::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => $titleLocales,
                    'research_question' => $questionLocales,
                    'methodology_summary' => $methodLocales,
                    'findings_summary' => $findingsLocales,
                    'significance' => $significanceLocales,
                    'full_paper_type' => $isExternal ? FullPaperType::EXTERNAL_LINK : FullPaperType::PDF_UPLOAD,
                    'external_url' => $isExternal ? 'https://example.com/papers/'.$slug : null,
                    'published_year' => 2020 + ($i % 6),
                    'co_authors' => ['Dr. A. Rahman', 'Dr. S. Khan'],
                    'is_published' => true,
                ]
            );
        }
    }
}
