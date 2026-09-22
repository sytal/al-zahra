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
            'site_tagline' => ['en' => 'Understanding minds, one language at a time.'],
            'contact_email' => 'hello@alzahra.institute',
            'contact_phone' => '',
            'footer_about_text' => ['en' => 'Al Zahra Institute is dedicated to neurolinguistic research and education.'],
            'footer_links' => [
                ['label' => ['en' => 'About'], 'url' => '/about'],
                ['label' => ['en' => 'Contact'], 'url' => '/contact'],
            ],
            'social_links' => [],
            'mission_text' => ['en' => 'To advance understanding of language and the mind through research and education.'],
            'vision_text' => ['en' => 'A world where neurolinguistic insight is accessible to everyone.'],
            'newsletter_enabled' => true,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
