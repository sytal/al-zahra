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
            'site_tagline' => ['en' => 'Understanding minds, one language at a time.', 'ur' => 'ایک وقت میں ایک زبان، ذہنوں کو سمجھنے کا سفر۔', 'hi' => 'एक-एक भाषा के ज़रिए मन को समझना।', 'fa' => 'فهم ذهن\u200cها، یک زبان در هر گام.', 'ur-roman' => 'Ek waqt mein ek zaban, zehnon ko samajhne ka safar.'],
            'contact_email' => 'hello@alzahra.institute',
            'contact_phone' => '',
            'footer_about_text' => ['en' => 'Al Zahra Institute is dedicated to neurolinguistic research and education.', 'ur' => 'الزہرا انسٹی ٹیوٹ عصبی لسانیات کی تحقیق اور تعلیم کے لیے وقف ہے۔', 'hi' => 'अल ज़हरा इंस्टीट्यूट तंत्रिका-भाषाविज्ञान के शोध और शिक्षा के लिए समर्पित है।', 'fa' => 'مؤسسه الزهرا به پژوهش و آموزش در زمینه عصب\u200cزبان\u200cشناسی اختصاص دارد.', 'ur-roman' => 'Al Zahra Institute neurolinguistics ki tehqeeq aur taleem ke liye waqf hai.'],
            'footer_links' => [
                ['label' => ['en' => 'About', 'ur' => 'تعارف', 'hi' => 'परिचय', 'fa' => 'درباره ما', 'ur-roman' => 'Taaruf'], 'url' => '/about'],
                ['label' => ['en' => 'Contact', 'ur' => 'رابطہ', 'hi' => 'संपर्क', 'fa' => 'تماس', 'ur-roman' => 'Rabta'], 'url' => '/contact'],
            ],
            'social_links' => [],
            'mission_text' => ['en' => 'To advance understanding of language and the mind through research and education.', 'ur' => 'تحقیق اور تعلیم کے ذریعے زبان اور ذہن کی سمجھ کو آگے بڑھانا۔', 'hi' => 'शोध और शिक्षा के माध्यम से भाषा और मन की समझ को आगे बढ़ाना।', 'fa' => 'پیشبرد درک زبان و ذهن از راه پژوهش و آموزش.', 'ur-roman' => 'Tehqeeq aur taleem ke zariye zaban aur zehn ki samajh ko aage barhana.'],
            'vision_text' => ['en' => 'A world where neurolinguistic insight is accessible to everyone.', 'ur' => 'ایک ایسی دنیا جہاں عصبی لسانیات کی بصیرت ہر کسی کے لیے قابل رسائی ہو۔', 'hi' => 'एक ऐसी दुनिया जहाँ तंत्रिका-भाषाविज्ञान की समझ सबके लिए सुलभ हो।', 'fa' => 'جهانی که در آن بینش عصب\u200cزبان\u200cشناسی برای همه در دسترس باشد.', 'ur-roman' => 'Aisi duniya jahan neurolinguistics ki basirat har kisi ke liye dastiyab ho.'],
            'newsletter_enabled' => true,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
