<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{
    use HasUuid, HasTranslations, SoftDeletes;

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
