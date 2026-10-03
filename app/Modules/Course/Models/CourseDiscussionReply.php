<?php

namespace App\Modules\Course\Models;

use App\Models\User;
use App\Support\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Support\LogOptions;

class CourseDiscussionReply extends Model
{
    use HasActivityLog {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Discussion reply';

    protected $fillable = [
        'course_block_id',
        'user_id',
        'author_id',
        'body',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(CourseBlock::class, 'course_block_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['body']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [
            'title' => $this->author?->name ?? '',
            'block' => $this->block?->title ?? '',
        ];
    }
}
