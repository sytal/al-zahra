<?php

namespace App\Modules\Course\Models;

use App\Support\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CourseBlockProgress extends Model implements HasMedia
{
    use HasActivityLog, InteractsWithMedia {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Block progress';

    protected $table = 'course_block_progress';

    protected $fillable = [
        'enrollment_id',
        'course_block_id',
        'status',
        'completed_at',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'data' => 'array',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function block(): BelongsTo
    {
        return $this->belongsTo(CourseBlock::class, 'course_block_id');
    }

    /**
     * Student-submitted assignment/research-activity files (distinct from
     * CourseBlock's admin 'attachments' collection, a different concern).
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('submission');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['status', 'completed_at']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->block?->title ?? '',
            'student' => $this->enrollment?->user?->name ?? '',
        ];
    }
}
