<?php

namespace App\Modules\Course\Models;

use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Translatable\HasTranslations;

class CourseBlock extends Model
{
    use HasActivityLog, HasTranslations, HasUuid, SoftDeletes {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Block';

    protected $fillable = [
        'course_module_id',
        'type',
        'title',
        'is_preview',
        'sort_order',
        'estimated_minutes',
        'content',
    ];

    public array $translatable = ['title'];

    protected function casts(): array
    {
        return [
            'is_preview' => 'boolean',
            'content' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(CourseBlockProgress::class);
    }

    public function discussionReplies(): HasMany
    {
        return $this->hasMany(CourseDiscussionReply::class);
    }

    public function progressFor(Enrollment $enrollment): ?CourseBlockProgress
    {
        return $this->progress()->where('enrollment_id', $enrollment->id)->first();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['title', 'type', 'is_preview', 'sort_order']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return ['module' => $this->module?->title ?? ''];
    }
}
