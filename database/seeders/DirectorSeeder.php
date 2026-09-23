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
                'professional_title' => ['en' => 'Neurolinguist & Language Researcher'],
                'tagline' => ['en' => 'Helping minds understand language, one insight at a time.'],
                'bio_short' => ['en' => 'A researcher and educator dedicated to making neurolinguistics accessible to everyone.'],
                'bio_full' => ['en' => 'Full biography to be finalized with the director — placeholder content pending real copy.'],
                'credentials' => ['PhD in Neurolinguistics'],
                'research_interests' => ['en' => ['Language acquisition', 'Bilingual cognition']],
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
