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

        foreach ($titles as $i => $title) {
            $slug = Str::slug($title);

            Article::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => ['en' => $title],
                    'excerpt' => ['en' => Str::limit($title, 90).' — an evidence-based look at what the research tells us.'],
                    'body' => ['en' => "<p>{$title}.</p><p>Placeholder body content — full article pending director review.</p>"],
                    'is_published' => $i % 4 !== 0,
                    'published_at' => $i % 4 !== 0 ? now()->subDays($i) : null,
                ]
            )->tags()->sync(
                $tags->random(min(2, $tags->count()))->pluck('id')
            );
        }
    }
}
