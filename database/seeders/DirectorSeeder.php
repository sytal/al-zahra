<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Director\Models\Director;
use Illuminate\Database\Seeder;

class DirectorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'director@alzahra.institute')->first();

        Director::firstOrCreate(
            ['user_id' => $user?->id],
            [
                'full_name' => 'Dr. Al Zahra Director',
                'professional_title' => ['en' => 'Neurolinguist & Language Researcher', 'ur' => 'ماہرِ عصبی لسانیات اور زبان کی محقق', 'hi' => 'तंत्रिका-भाषाविद् और भाषा शोधकर्ता', 'fa' => 'عصب\u200cزبان\u200cشناس و پژوهشگر زبان', 'ur-roman' => 'Neurolinguist aur zaban ki muhaqqiq'],
                'tagline' => ['en' => 'Helping minds understand language, one insight at a time.', 'ur' => 'ذہنوں کو زبان سمجھنے میں مدد، ایک بصیرت کے ساتھ۔', 'hi' => 'हर अंतर्दृष्टि के साथ मन को भाषा समझने में मदद।', 'fa' => 'کمک به ذهن\u200cها برای فهم زبان، با هر بینش تازه.', 'ur-roman' => 'Zehnon ko zaban samajhne mein madad, ek basirat ke sath.'],
                'bio_short' => ['en' => 'A researcher and educator dedicated to making neurolinguistics accessible to everyone.', 'ur' => 'ایک محقق اور معلم جو عصبی لسانیات کو سب کے لیے قابل رسائی بنانے کے لیے وقف ہیں۔', 'hi' => 'एक शोधकर्ता और शिक्षक, जो तंत्रिका-भाषाविज्ञान को सबके लिए सुलभ बनाने के लिए समर्पित हैं।', 'fa' => 'پژوهشگر و مربی\u200cای که خود را وقف در دسترس قرار دادن عصب\u200cزبان\u200cشناسی برای همگان کرده است.', 'ur-roman' => 'Ek muhaqqiq aur muallim jo neurolinguistics ko sab ke liye dastiyab banane ke liye waqf hain.'],
                'bio_full' => ['en' => 'Full biography to be finalized with the director, placeholder content pending real copy.', 'ur' => 'مکمل سوانح ڈائریکٹر کے ساتھ حتمی کی جائے گی، فی الحال عارضی متن ہے۔', 'hi' => 'पूरी जीवनी निदेशक के साथ अंतिम की जाएगी, फ़िलहाल यह अस्थायी पाठ है।', 'fa' => 'زندگی\u200cنامه کامل با مدیر نهایی خواهد شد و فعلاً متن موقت است.', 'ur-roman' => 'Mukammal sawaneh director ke sath final kiya jayega, filhal aarzi matan hai.'],
                'credentials' => ['PhD in Neurolinguistics'],
                'research_interests' => ['en' => ['Language acquisition', 'Bilingual cognition'], 'ur' => ['زبان کا حصول', 'دو لسانی ادراک'], 'hi' => ['भाषा अर्जन', 'द्विभाषी संज्ञान'], 'fa' => ['اکتساب زبان', 'شناخت دوزبانه'], 'ur-roman' => ['Zaban ka husool', 'Do lisani idrak']],
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/alzahra-director',
                    'researchgate' => 'https://researchgate.net/profile/alzahra-director',
                    'twitter' => null,
                    'email' => 'director@alzahra.institute',
                ],
                'is_published' => true,
            ]
        );
    }
}
