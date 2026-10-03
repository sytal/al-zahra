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

        $director = Director::firstOrCreate(
            ['user_id' => $user?->id],
            [
                'full_name' => 'Dr. Syyeda Arrabah Naqvi',
                'professional_title' => ['en' => 'Lecturer in Literature & Language', 'ur' => 'لیکچرار برائے ادب و زبان', 'hi' => 'साहित्य एवं भाषा व्याख्याता', 'fa' => 'مدرس ادبیات و زبان', 'ur-roman' => 'Lecturer baraye adab o zaban'],
                'tagline' => ['en' => 'Known for her strategic mindset, disciplined leadership, and calm decision-making.', 'ur' => 'وہ اپنی حکمتِ عملی پر مبنی سوچ، نظم و ضبط پر مبنی قیادت، اور پرسکون فیصلہ سازی کے لیے معروف ہیں۔', 'hi' => 'वे अपनी रणनीतिक सोच, अनुशासित नेतृत्व और शांत निर्णय-क्षमता के लिए जानी जाती हैं।', 'fa' => 'او به‌خاطر ذهنیت راهبردی، رهبری منظم و تصمیم‌گیری آرام خود شناخته شده است.', 'ur-roman' => 'Wo apni strategic soch, nazm o zabt par mabni qiyadat, aur pursukoon faisla-sazi ke liye maroof hain.'],
                'bio_short' => ['en' => 'A lecturer in literature and language whose published research explores the relationship between the brain and language, known for her strategic mindset, disciplined leadership, and calm decision-making.', 'ur' => 'ادب اور زبان کی لیکچرار، جن کی تحقیق دماغ اور زبان کے تعلق پر مرکوز ہے، اپنی حکمتِ عملی پر مبنی سوچ، نظم و ضبط پر مبنی قیادت اور پرسکون فیصلہ سازی کے لیے معروف ہیں۔', 'hi' => 'साहित्य और भाषा की व्याख्याता, जिनका शोध मस्तिष्क-भाषा संबंध पर केंद्रित है, अपनी रणनीतिक सोच, अनुशासित नेतृत्व और शांत निर्णय-क्षमता के लिए जानी जाती हैं।', 'fa' => 'مدرس ادبیات و زبان که پژوهش او بر رابطه مغز و زبان متمرکز است و به‌خاطر ذهنیت راهبردی، رهبری منظم و تصمیم‌گیری آرام شناخته شده است.', 'ur-roman' => 'Adab aur zaban ki lecturer, jin ki tehqeeq dimagh aur zaban ke talluq par markoz hai, apni strategic soch, nazm o zabt par mabni qiyadat aur pursukoon faisla-sazi ke liye maroof hain.'],
                'bio_full' => ['en' => 'Dr. Syyeda Arrabah Naqvi is a lecturer in literature and language whose published research in neurolinguistics explores the relationship between the brain and language. She is known for her strategic mindset, disciplined leadership, and calm decision-making, qualities that inform both her teaching practice and her leadership at Al Zahra Institute.', 'ur' => 'ڈاکٹر سیدہ عربہ نقوی ادب اور زبان کی لیکچرار ہیں جن کی عصبی لسانیات میں شائع شدہ تحقیق دماغ اور زبان کے تعلق کا جائزہ لیتی ہے۔ وہ اپنی حکمتِ عملی پر مبنی سوچ، نظم و ضبط پر مبنی قیادت اور پرسکون فیصلہ سازی کے لیے معروف ہیں، جو ان کی تدریس اور الزہرا انسٹی ٹیوٹ میں قیادت دونوں کی رہنمائی کرتی ہیں۔', 'hi' => 'डॉ. सैयदा अराबाह नक़वी साहित्य और भाषा की व्याख्याता हैं, जिनका तंत्रिका-भाषाविज्ञान में प्रकाशित शोध मस्तिष्क और भाषा के संबंध की पड़ताल करता है। वे अपनी रणनीतिक सोच, अनुशासित नेतृत्व और शांत निर्णय-क्षमता के लिए जानी जाती हैं, जो उनके शिक्षण और अल ज़हरा इंस्टिट्यूट में नेतृत्व दोनों को दिशा देती हैं।', 'fa' => 'دکتر سیده عربه نقوی مدرس ادبیات و زبان است که پژوهش منتشرشده او در زبان‌شناسی عصبی به بررسی رابطه مغز و زبان می‌پردازد. او به‌خاطر ذهنیت راهبردی، رهبری منظم و تصمیم‌گیری آرام خود شناخته شده است، ویژگی‌هایی که هم بر شیوه تدریس و هم بر رهبری او در مؤسسه الزهرا تأثیر می‌گذارد.', 'ur-roman' => 'Dr. Syyeda Arrabah Naqvi adab aur zaban ki lecturer hain jin ki neurolinguistics mein shaya shuda tehqeeq dimagh aur zaban ke talluq ka jaiza leti hai. Wo apni strategic soch, nazm o zabt par mabni qiyadat aur pursukoon faisla-sazi ke liye maroof hain, jo un ki tadrees aur Al Zahra Institute mein qiyadat dono ki rehnumai karti hain.'],
                'credentials' => ['PhD in Neurolinguistics'],
                'research_interests' => ['en' => ['Neurolinguistics', 'Brain–Language Relationships'], 'ur' => ['عصبی لسانیات', 'دماغ اور زبان کا تعلق'], 'hi' => ['तंत्रिका-भाषाविज्ञान', 'मस्तिष्क-भाषा संबंध'], 'fa' => ['عصب‌زبان‌شناسی', 'رابطه مغز و زبان'], 'ur-roman' => ['Neurolinguistics', 'Dimagh aur zaban ka talluq']],
                'social_links' => [
                    'linkedin' => 'https://linkedin.com/in/alzahra-director',
                    'researchgate' => 'https://researchgate.net/profile/alzahra-director',
                    'twitter' => null,
                    'email' => 'director@alzahra.institute',
                ],
                'is_published' => true,
            ]
        );

        $photoPath = base_path('database/seeders/data/director.png');
        if (file_exists($photoPath) && $director->getFirstMedia('profile_photo') === null) {
            $director->addMedia($photoPath)->preservingOriginal()->toMediaCollection('profile_photo');
        }
    }
}
