<?php

namespace Database\Seeders;

use App\Modules\Category\Models\Category;
use App\Modules\Resource\Models\Resource;
use App\Support\Enums\CategoryType;
use App\Support\Enums\ResourceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::where('type', CategoryType::RESOURCE)->get();

        if ($categories->isEmpty()) {
            return;
        }

        $items = [
            ['title' => 'Bilingual Vocabulary Tracker Worksheet', 'type' => ResourceType::TEMPLATE, 'free' => true],
            ['title' => 'Early Language Milestones Checklist', 'type' => ResourceType::GUIDE, 'free' => true],
            ['title' => 'Speech Development Screening Questionnaire', 'type' => ResourceType::QUESTIONNAIRE, 'free' => true],
            ['title' => 'Classroom Bilingual Activity Pack', 'type' => ResourceType::PDF, 'free' => true],
            ['title' => 'Parent Guide to Supporting Two Languages at Home', 'type' => ResourceType::GUIDE, 'free' => true],
            ['title' => 'Reading Readiness Assessment Tool', 'type' => ResourceType::QUESTIONNAIRE, 'free' => true],
            ['title' => 'Advanced Language Therapy Planner', 'type' => ResourceType::TEMPLATE, 'free' => false],
            ['title' => 'Complete Neurolinguistics Reference Pack', 'type' => ResourceType::PDF, 'free' => false],
        ];

        foreach ($items as $i => $item) {
            $slug = Str::slug($item['title']);

            Resource::firstOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => ['en' => $item['title']],
                    'description' => ['en' => $item['title'].' — a practical, ready-to-use resource for supporting language development.'],
                    'resource_type' => $item['type'],
                    'is_free' => $item['free'],
                    'price' => $item['free'] ? null : 9.99,
                    'is_published' => true,
                ]
            );
        }
    }
}
