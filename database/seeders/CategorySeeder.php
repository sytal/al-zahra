<?php

namespace Database\Seeders;

use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            CategoryType::ARTICLE->value => ['Language Development', 'Bilingual Minds', 'Learning Science', 'Parenting & Language'],
            CategoryType::RESEARCH->value => ['Neurolinguistics', 'Cognitive Development', 'Applied Linguistics', 'Speech & Language Disorders'],
            CategoryType::RESOURCE->value => ['Worksheets', 'Assessment Tools', 'Parent Guides', 'Classroom Activities'],
            CategoryType::COURSE->value => ['Foundations', 'Professional Development', 'Family Workshops', 'Advanced Topics'],
        ];

        foreach ($names as $type => $labels) {
            foreach ($labels as $label) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($type.'-'.$label)],
                    ['name' => ['en' => $label], 'type' => $type]
                );
            }
        }
    }
}
