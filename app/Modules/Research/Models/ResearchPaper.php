<?php

namespace App\Modules\Research\Models;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Support\Enums\FullPaperType;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ResearchPaper extends Model
{
    use HasUuid, HasTranslations, SoftDeletes;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'research_question',
        'methodology_summary',
        'findings_summary',
        'significance',
        'full_paper_type',
        'external_url',
        'published_year',
        'co_authors',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    public array $translatable = [
        'title',
        'research_question',
        'methodology_summary',
        'findings_summary',
        'significance',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'full_paper_type' => FullPaperType::class,
            'co_authors' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
