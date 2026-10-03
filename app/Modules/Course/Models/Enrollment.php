<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use App\Support\Enums\EnrollmentStatus;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Support\LogOptions;

class Enrollment extends Model
{
    use HasActivityLog, HasUuid {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Enrollment';

    protected $fillable = [
        'user_id',
        'course_id',
        'course_batch_id',
        'status',
        'progress_percent',
        'enrolled_at',
        'completed_at',
        'left_at',
        'final_score',
    ];

    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
            'enrolled_at' => 'datetime',
            'completed_at' => 'datetime',
            'left_at' => 'datetime',
            'final_score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(CourseBatch::class, 'course_batch_id');
    }

    public function batchEnrollment(): HasOne
    {
        return $this->hasOne(CourseBatchEnrollment::class);
    }

    public function blockProgress(): HasMany
    {
        return $this->hasMany(CourseBlockProgress::class);
    }

    /**
     * Number of courses the given user is currently active in (not left,
     * not completed) — powers the 2-course enrollment cap.
     */
    public static function activeCourseCount(User $user): int
    {
        return static::query()
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->where('status', EnrollmentStatus::ACTIVE)
            ->count();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['status', 'progress_percent', 'completed_at']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->user?->name ?? '',
            'course' => $this->course?->title ?? '',
        ];
    }
}
