<?php

namespace App\Modules\Course\Models;

use App\Support\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;

class CourseBatchEnrollment extends Model
{
    use HasActivityLog {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Batch enrollment';

    protected $fillable = [
        'course_batch_id',
        'enrollment_id',
        'roll_number',
        'status',
        'waitlist_position',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(CourseBatch::class, 'course_batch_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['roll_number', 'status', 'waitlist_position']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->roll_number ?? '',
            'batch' => $this->batch?->label ?? '',
        ];
    }
}
