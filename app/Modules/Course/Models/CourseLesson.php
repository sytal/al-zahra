<?php

namespace App\Modules\Course\Models;

use App\Support\Enums\LessonContentType;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CourseLesson extends Model
{
    use HasUuid, HasTranslations, SoftDeletes;

    protected $fillable = [
        'course_id',
        'title',
        'content_type',
        'body',
        'video_url',
        'duration_minutes',
        'is_preview',
        'sort_order',
    ];

    public array $translatable = ['title', 'body'];

    protected function casts(): array
    {
        return [
            'content_type' => LessonContentType::class,
            'is_preview' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
