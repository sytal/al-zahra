<?php

namespace Database\Seeders;

use App\Modules\Setting\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'Al Zahra Institute',
            'site_tagline' => ['en' => 'Understanding minds, one language at a time.', 'ur' => 'ایک وقت میں ایک زبان، ذہنوں کو سمجھنے کا سفر۔', 'hi' => 'एक-एक भाषा के ज़रिए मन को समझना।', 'fa' => 'فهم ذهن\‌ها، یک زبان در هر گام.', 'ur-roman' => 'Ek waqt mein ek zaban, zehnon ko samajhne ka safar.'],
            'contact_email' => 'hello@alzahra.institute',
            'contact_phone' => '+92 21 1234 5678',
            'contact_address' => ['en' => '12 Garden Road, Clifton, Karachi, Pakistan', 'ur' => '12 گارڈن روڈ، کلفٹن، کراچی، پاکستان', 'hi' => '12 गार्डन रोड, क्लिफ़्टन, कराची, पाकिस्तान', 'fa' => 'کراچی، کلیفتون، جاده گاردن، شماره ۱۲، پاکستان', 'ur-roman' => '12 Garden Road, Clifton, Karachi, Pakistan'],
            'footer_about_text' => ['en' => 'Al Zahra Institute is dedicated to neurolinguistic research and education.', 'ur' => 'الزہرا انسٹی ٹیوٹ عصبی لسانیات کی تحقیق اور تعلیم کے لیے وقف ہے۔', 'hi' => 'अल ज़हरा इंस्टीट्यूट तंत्रिका-भाषाविज्ञान के शोध और शिक्षा के लिए समर्पित है।', 'fa' => 'مؤسسه الزهرا به پژوهش و آموزش در زمینه عصب\‌زبان\‌شناسی اختصاص دارد.', 'ur-roman' => 'Al Zahra Institute neurolinguistics ki tehqeeq aur taleem ke liye waqf hai.'],
            'footer_links' => [
                ['label' => ['en' => 'About', 'ur' => 'تعارف', 'hi' => 'परिचय', 'fa' => 'درباره ما', 'ur-roman' => 'Taaruf'], 'url' => '/about'],
                ['label' => ['en' => 'Contact', 'ur' => 'رابطہ', 'hi' => 'संपर्क', 'fa' => 'تماس', 'ur-roman' => 'Rabta'], 'url' => '/contact'],
            ],
            'social_links' => [],
            'mission_text' => ['en' => 'To advance understanding of language and the mind through research and education.', 'ur' => 'تحقیق اور تعلیم کے ذریعے زبان اور ذہن کی سمجھ کو آگے بڑھانا۔', 'hi' => 'शोध और शिक्षा के माध्यम से भाषा और मन की समझ को आगे बढ़ाना।', 'fa' => 'پیشبرد درک زبان و ذهن از راه پژوهش و آموزش.', 'ur-roman' => 'Tehqeeq aur taleem ke zariye zaban aur zehn ki samajh ko aage barhana.'],
            'vision_text' => ['en' => 'A world where neurolinguistic insight is accessible to everyone.', 'ur' => 'ایک ایسی دنیا جہاں عصبی لسانیات کی بصیرت ہر کسی کے لیے قابل رسائی ہو۔', 'hi' => 'एक ऐसी दुनिया जहाँ तंत्रिका-भाषाविज्ञान की समझ सबके लिए सुलभ हो।', 'fa' => 'جهانی که در آن بینش عصب\‌زبان\‌شناسی برای همه در دسترس باشد.', 'ur-roman' => 'Aisi duniya jahan neurolinguistics ki basirat har kisi ke liye dastiyab ho.'],
            'newsletter_enabled' => true,
            'contact_hours' => ['mon' => '09:00 - 17:00', 'tue' => '09:00 - 17:00', 'wed' => '09:00 - 17:00', 'thu' => '09:00 - 17:00', 'fri' => null, 'sat' => '10:00 - 14:00', 'sun' => null],
            'testimonials' => [
                [
                    'demo' => true,
                    'quote' => ['en' => 'The consultation gave us a clear plan for our son. We finally understood what was happening and what to do next.', 'ur' => 'مشاورت نے ہمیں اپنے بیٹے کے لیے واضح منصوبہ دیا۔ ہم نے آخرکار سمجھا کہ کیا ہو رہا ہے اور آگے کیا کرنا ہے۔', 'hi' => 'परामर्श से हमें अपने बेटे के लिए साफ़ योजना मिली। आख़िरकार समझ आया कि क्या हो रहा है और आगे क्या करना है।', 'fa' => 'مشاوره برای پسرمان برنامه‌ای روشن داد. بالاخره فهمیدیم چه اتفاقی می‌افتد و قدم بعدی چیست.', 'ur-roman' => 'Mushawarat ne humein apne bete ke liye wazeh mansooba diya. Akhir hum samjhe ke kya ho raha hai aur aage kya karna hai.'],
                    'name' => ['en' => 'Sample Parent', 'ur' => 'نمونہ والدین', 'hi' => 'नमूना अभिभावक', 'fa' => 'والد نمونه', 'ur-roman' => 'Namuna Walidain'],
                    'role' => ['en' => 'Parent (demo)', 'ur' => 'والد (نمونہ)', 'hi' => 'अभिभावक (नमूना)', 'fa' => 'والد (نمونه)', 'ur-roman' => 'Walid (namuna)'],
                ],
                [
                    'demo' => true,
                    'quote' => ['en' => 'The course explains hard ideas in plain language. I use the exercises with my students every week.', 'ur' => 'کورس مشکل خیالات کو سادہ زبان میں سمجھاتا ہے۔ میں ہر ہفتے مشقیں اپنے طلبہ کے ساتھ استعمال کرتا ہوں۔', 'hi' => 'कोर्स कठिन विचारों को सरल भाषा में समझाता है। मैं हर हफ़्ते अभ्यास अपने विद्यार्थियों के साथ करता हूँ।', 'fa' => 'دوره مفاهیم دشوار را ساده توضیح می‌دهد. هر هفته تمرین‌ها را با دانشجویانم انجام می‌دهم.', 'ur-roman' => 'Course mushkil khayalat ko saadah zaban mein samjhata hai. Main har hafte mashqein apne talba ke sath istemal karta hoon.'],
                    'name' => ['en' => 'Sample Teacher', 'ur' => 'نمونہ استاد', 'hi' => 'नमूना शिक्षक', 'fa' => 'معلم نمونه', 'ur-roman' => 'Namuna Ustaad'],
                    'role' => ['en' => 'Teacher (demo)', 'ur' => 'استاد (نمونہ)', 'hi' => 'शिक्षक (नमूना)', 'fa' => 'معلم (نمونه)', 'ur-roman' => 'Ustaad (namuna)'],
                ],
                [
                    'demo' => true,
                    'quote' => ['en' => 'Clear, careful research summaries I can actually cite. A rare mix of rigor and readability.', 'ur' => 'واضح اور محتاط تحقیقی خلاصے جن کا میں حوالہ دے سکتا ہوں۔ سختی اور آسانی کا نایاب امتزاج۔', 'hi' => 'साफ़ और सावधान शोध सार जिनका मैं हवाला दे सकता हूँ। कठोरता और सरलता का दुर्लभ मेल।', 'fa' => 'خلاصه‌های پژوهشی روشن و دقیق که می‌توانم به آن‌ها ارجاع دهم. ترکیبی کمیاب از دقت و روانی.', 'ur-roman' => 'Wazeh aur mohtaat tehqeeqi khulase jin ka main hawala de sakta hoon. Sakhti aur aasani ka nayaab imtizaj.'],
                    'name' => ['en' => 'Sample Researcher', 'ur' => 'نمونہ محقق', 'hi' => 'नमूना शोधकर्ता', 'fa' => 'پژوهشگر نمونه', 'ur-roman' => 'Namuna Muhaqqiq'],
                    'role' => ['en' => 'Graduate student (demo)', 'ur' => 'گریجویٹ طالب علم (نمونہ)', 'hi' => 'स्नातक विद्यार्थी (नमूना)', 'fa' => 'دانشجوی کارشناسی ارشد (نمونه)', 'ur-roman' => 'Graduate talib-e-ilm (namuna)'],
                ],
            ],
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
