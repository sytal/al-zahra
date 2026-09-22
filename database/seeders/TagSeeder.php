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

        foreach ($tags as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => ['en' => $name]]
            );
        }
    }
}
