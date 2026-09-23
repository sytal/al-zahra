<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Modules\Research\Models\ResearchPaper;
use App\Support\Enums\CategoryType;
use App\Support\Enums\FullPaperType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResearchPaperSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'director@alzahra.institute')->first();
        $categories = Category::where('type', CategoryType::RESEARCH)->get();

        if (! $author || $categories->isEmpty()) {
            return;
        }

        $titles = [
            'Cross-Linguistic Transfer in Early Bilingual Learners',
            'Neural Correlates of Code-Switching in Adult Bilinguals',
            'The Effect of Bilingualism on Executive Function Development',
            'Phonological Awareness as a Predictor of Reading Success',
            'Language Attrition in Heritage Speakers: A Longitudinal Study',
            'Working Memory and Second Language Vocabulary Acquisition',
        ];

        foreach ($titles as $i => $title) {
            $slug = Str::slug($title);
            $isExternal = $i % 2 === 0;

            ResearchPaper::firstOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'category_id' => $categories[$i % $categories->count()]->id,
                    'title' => ['en' => $title],
                    'research_question' => ['en' => 'What role does '.strtolower(explode(' in ', $title)[0]).' play in language development?'],
                    'methodology_summary' => ['en' => 'Mixed-methods study combining behavioral testing and longitudinal observation across a diverse participant sample.'],
                    'findings_summary' => ['en' => 'Findings suggest a measurable, statistically significant relationship consistent with prior literature in the field.'],
                    'significance' => ['en' => 'These results have direct implications for educators and clinicians working with bilingual populations.'],
                    'full_paper_type' => $isExternal ? FullPaperType::EXTERNAL_LINK : FullPaperType::PDF_UPLOAD,
                    'external_url' => $isExternal ? 'https://example.com/papers/'.$slug : null,
                    'published_year' => 2020 + ($i % 6),
                    'co_authors' => ['Dr. A. Rahman', 'Dr. S. Khan'],
                    'is_published' => true,
                ]
            );
        }
    }
}
