<?php

namespace App\Modules\Research\Models;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Support\CacheService;
use App\Support\Enums\FullPaperType;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class ResearchPaper extends Model implements HasMedia
{
    use HasActivityLog, HasTranslations, HasUuid, InteractsWithMedia, SoftDeletes;

    protected string $activityLogLabel = 'Research paper';

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('paper_file')->singleFile();
        $this->addMediaCollection('cover_image')->singleFile();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Explicit cache invalidation on save/delete (docs/CLAUDE.md Section 13).
     * Only the slug-detail key is forgotten here since list-cache keys are
     * combinatorial (per filter/search/page) and not practically
     * enumerable; list staleness is instead bounded by CacheService::LIST_TTL.
     */
    protected static function booted(): void
    {
        $forget = function (self $paper): void {
            app(CacheService::class)->forget("research-papers:slug:{$paper->slug}");
        };

        static::saved($forget);
        static::deleted($forget);
    }
}
