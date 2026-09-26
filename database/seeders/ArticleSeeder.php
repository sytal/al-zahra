<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Models\Tag;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'director@alzahra.institute')->first();
        $categories = Category::where('type', CategoryType::ARTICLE)->get();
        $tags = Tag::all();

        if (! $author || $categories->isEmpty()) {
            return;
        }

        $titles = [
            'How Bilingual Brains Process Two Languages at Once',
            'The Critical Window for Early Language Acquisition',
            'Why Code-Switching Is a Sign of Linguistic Skill, Not Confusion',
            'Reading to Your Child: What the Research Actually Says',
            'Understanding Speech Delays: When to Seek Help',
            'The Link Between Vocabulary Size and Academic Success',
            'How Music Training Shapes Language Development',
            'Screen Time and Language: What Parents Should Know',
            'Building Vocabulary Through Everyday Conversation',
            'The Neuroscience Behind Learning a Second Language as an Adult',
            'Signs of Strong Language Development in Toddlers',
            'How Multilingual Households Shape Cognitive Flexibility',
            'The Role of Storytelling in Early Literacy',
            'Common Myths About Bilingual Education, Debunked',
            'Supporting Language Growth in Children with Learning Differences',
        ];

        $translations = [
            ['ur' => 'دو لسانی دماغ بیک وقت دو زبانوں کو کیسے سنبھالتے ہیں', 'hi' => 'द्विभाषी मस्तिष्क एक साथ दो भाषाओं को कैसे संसाधित करता है', 'fa' => 'مغز دوزبانه چگونه دو زبان را هم‌زمان پردازش می‌کند', 'ur-roman' => 'Do lisani dimagh ek waqt mein do zabanon ko kaise sambhalte hain'],
            ['ur' => 'ابتدائی زبان سیکھنے کا نازک دورانیہ', 'hi' => 'प्रारंभिक भाषा अर्जन की महत्वपूर्ण अवधि', 'fa' => 'دوره حساس یادگیری زبان در سال‌های نخست', 'ur-roman' => 'Ibtidai zaban seekhne ka nazuk daurania'],
            ['ur' => 'کوڈ سوئچنگ الجھن نہیں بلکہ لسانی مہارت کی علامت ہے', 'hi' => 'कोड-स्विचिंग भ्रम नहीं, भाषाई कौशल का संकेत है', 'fa' => 'تغییر کد زبانی نشانه مهارت زبانی است، نه سردرگمی', 'ur-roman' => 'Code-switching uljhan nahin balke lisani maharat ki alamat hai'],
            ['ur' => 'اپنے بچے کو کتاب پڑھ کر سنانا: تحقیق واقعی کیا کہتی ہے', 'hi' => 'अपने बच्चे को पढ़कर सुनाना: शोध वास्तव में क्या कहता है', 'fa' => 'کتاب‌خوانی برای کودک: پژوهش‌ها واقعاً چه می‌گویند', 'ur-roman' => 'Apne bachche ko kitab parh kar sunana: tehqeeq haqeeqatan kya kehti hai'],
            ['ur' => 'گفتار میں تاخیر کو سمجھنا: مدد کب لینی چاہیے', 'hi' => 'बोलने में देरी को समझना: मदद कब लेनी चाहिए', 'fa' => 'شناخت تأخیر گفتار: چه زمانی باید کمک گرفت', 'ur-roman' => 'Guftar mein takheer ko samajhna: madad kab leni chahiye'],
            ['ur' => 'ذخیرہ الفاظ کے حجم اور تعلیمی کامیابی کا تعلق', 'hi' => 'शब्दावली के आकार और शैक्षणिक सफलता का संबंध', 'fa' => 'پیوند میان اندازه دایره واژگان و موفقیت تحصیلی', 'ur-roman' => 'Zakheera-e-alfaz ke hajm aur taleemi kamyabi ka talluq'],
            ['ur' => 'موسیقی کی تربیت زبان کی نشوونما کو کیسے شکل دیتی ہے', 'hi' => 'संगीत प्रशिक्षण भाषा विकास को कैसे आकार देता है', 'fa' => 'آموزش موسیقی چگونه رشد زبان را شکل می‌دهد', 'ur-roman' => 'Moseeqi ki tarbiyat zaban ki nashonuma ko kaise shakl deti hai'],
            ['ur' => 'اسکرین ٹائم اور زبان: والدین کو کیا جاننا چاہیے', 'hi' => 'स्क्रीन टाइम और भाषा: अभिभावकों को क्या जानना चाहिए', 'fa' => 'زمان استفاده از صفحه‌نمایش و زبان: آنچه والدین باید بدانند', 'ur-roman' => 'Screen time aur zaban: walidain ko kya jaanna chahiye'],
            ['ur' => 'روزمرہ گفتگو کے ذریعے ذخیرہ الفاظ بڑھانا', 'hi' => 'रोज़मर्रा की बातचीत से शब्दावली बढ़ाना', 'fa' => 'افزایش دایره واژگان از راه گفت‌وگوی روزمره', 'ur-roman' => 'Rozmarra guftagu ke zariye zakheera-e-alfaz barhana'],
            ['ur' => 'بالغ عمر میں دوسری زبان سیکھنے کے پیچھے اعصابی سائنس', 'hi' => 'वयस्क के रूप में दूसरी भाषा सीखने के पीछे तंत्रिका विज्ञान', 'fa' => 'علوم اعصاب پشت یادگیری زبان دوم در بزرگسالی', 'ur-roman' => 'Balig umr mein doosri zaban seekhne ke peechhe asabi science'],
            ['ur' => 'چھوٹے بچوں میں زبان کی مضبوط نشوونما کی علامات', 'hi' => 'छोटे बच्चों में मज़बूत भाषा विकास के संकेत', 'fa' => 'نشانه‌های رشد قوی زبان در نوپایان', 'ur-roman' => 'Chhote bachchon mein zaban ki mazboot nashonuma ki alamaat'],
            ['ur' => 'کثیر لسانی گھرانے ذہنی لچک کو کیسے تشکیل دیتے ہیں', 'hi' => 'बहुभाषी परिवार संज्ञानात्मक लचीलेपन को कैसे आकार देते हैं', 'fa' => 'خانواده‌های چندزبانه چگونه انعطاف‌پذیری شناختی را شکل می‌دهند', 'ur-roman' => 'Kasir lisani gharane zehni lachak ko kaise tashkeel dete hain'],
            ['ur' => 'ابتدائی خواندگی میں کہانی سنانے کا کردار', 'hi' => 'प्रारंभिक साक्षरता में कहानी सुनाने की भूमिका', 'fa' => 'نقش قصه‌گویی در سواد آموزی نخستین', 'ur-roman' => 'Ibtidai khwandagi mein kahani sunane ka kirdar'],
            ['ur' => 'دو لسانی تعلیم کے بارے میں عام غلط فہمیاں، حقیقت کے ساتھ', 'hi' => 'द्विभाषी शिक्षा के बारे में आम भ्रांतियाँ, तथ्यों के साथ', 'fa' => 'باورهای نادرست رایج درباره آموزش دوزبانه و پاسخ آن‌ها', 'ur-roman' => 'Do lisani taleem ke bare mein aam ghalat fehmiyan, haqeeqat ke sath'],
            ['ur' => 'سیکھنے میں فرق رکھنے والے بچوں کی زبانی نشوونما میں معاونت', 'hi' => 'सीखने में भिन्नता वाले बच्चों के भाषा विकास में सहयोग', 'fa' => 'حمایت از رشد زبان در کودکان دارای تفاوت‌های یادگیری', 'ur-roman' => 'Seekhne mein farq rakhne wale bachchon ki zabani nashonuma mein moawinat'],
        ];

        $excerptSuffix = [
            'ur' => 'ثبوت پر مبنی جائزہ کہ تحقیق ہمیں کیا بتاتی ہے۔',
            'hi' => 'शोध हमें क्या बताता है, इस पर प्रमाण-आधारित दृष्टि।',
            'fa' => 'نگاهی مبتنی بر شواهد به آنچه پژوهش‌ها به ما می‌گویند.',
            'ur-roman' => 'Tehqeeq hamein kya batati hai, is par saboot par mabni jaiza.',
        ];

        $bodyNote = [
            'ur' => 'مکمل مضمون ڈائریکٹر کے جائزے کے بعد شائع کیا جائے گا۔',
            'hi' => 'पूरा लेख निदेशक की समीक्षा के बाद प्रकाशित किया जाएगा।',
            'fa' => 'متن کامل مقاله پس از بازبینی مدیر منتشر خواهد شد.',
            'ur-roman' => 'Mukammal mazmoon director ke jaize ke baad shaya kiya jayega.',
        ];

        foreach ($titles as $i => $title) {
            $slug = Str::slug($title);

            $titleLocales = ['en' => $title] + $translations[$i];
            $excerptLocales = ['en' => Str::limit($title, 90).' — an evidence-based look at what the research tells us.'];
            $bodyLocales = ['en' => "<p>{$title}.</p><p>Placeholder body content — full article pending director review.</p>"];

            foreach ($translations[$i] as $locale => $translated) {
                $excerptLocales[$locale] = $translated.': '.$excerptSuffix[$locale];
                $bodyLocales[$locale] = "<p>{$translated}.</p><p>{$bodyNote[$locale]}</p>";
            }

            Article::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => $titleLocales,
                    'excerpt' => $excerptLocales,
                    'body' => $bodyLocales,
                    'is_published' => $i % 4 !== 0,
                    'published_at' => $i % 4 !== 0 ? now()->subDays($i) : null,
                ]
            )->tags()->sync(
                $tags->random(min(2, $tags->count()))->pluck('id')
            );
        }
    }
}
