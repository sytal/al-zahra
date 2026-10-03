<?php

namespace App\Modules\Course\Models;

use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;

class CourseBatch extends Model
{
    use HasActivityLog, HasUuid, SoftDeletes {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Batch';

    protected $fillable = [
        'course_id',
        'label',
        'starts_at',
        'ends_at',
        'seats',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function batchEnrollments(): HasMany
    {
        return $this->hasMany(CourseBatchEnrollment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['label', 'starts_at', 'ends_at', 'seats']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->label,
            'course' => $this->course?->title ?? '',
        ];
    }
}
