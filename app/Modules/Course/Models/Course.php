<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

class Course extends Model implements HasMedia
{
    use HasActivityLog, HasTranslations, HasUuid, InteractsWithMedia, SoftDeletes;

    protected string $activityLogLabel = 'Course';

    protected $fillable = [
        'category_id',
        'instructor_id',
        'title',
        'slug',
        'short_description',
        'full_description',
        'level',
        'audience',
        'learning_outcomes',
        'estimated_duration_hours',
        'is_free',
        'price',
        'is_published',
        'enrolled_count',
        'meta_title',
        'meta_description',
    ];

    public array $translatable = [
        'title',
        'short_description',
        'full_description',
        'learning_outcomes',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'level' => CourseLevel::class,
            'audience' => CourseAudience::class,
            'is_free' => 'boolean',
            'is_published' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')->fit(Fit::Crop, 600, 400);
        $this->addMediaConversion('hero')->fit(Fit::Crop, 1200, 630);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class);
    }
}
