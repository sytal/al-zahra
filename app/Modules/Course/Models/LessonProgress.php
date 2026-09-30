<?php

namespace App\Modules\Course\Models;

use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;

class LessonProgress extends Model
{
    use HasActivityLog, HasUuid {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Lesson progress';

    protected $fillable = [
        'enrollment_id',
        'course_lesson_id',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(CourseLesson::class, 'course_lesson_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['completed_at']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->lesson?->title ?? '',
            'course' => $this->enrollment?->course?->title ?? '',
        ];
    }
}
