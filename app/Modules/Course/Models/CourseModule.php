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

class CourseModule extends Model
{
    use HasActivityLog, HasTranslations, HasUuid, SoftDeletes {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Module';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'sort_order',
    ];

    public array $translatable = ['title', 'description'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(CourseBlock::class)->orderBy('sort_order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['title', 'sort_order']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return ['course' => $this->course?->title ?? ''];
    }
}
