<?php

namespace App\Modules\Consultation\Models;

use App\Models\User;
use App\Support\Enums\ConsultationStatus;
use App\Support\Enums\ConsultationType;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use HasActivityLog, HasUuid, SoftDeletes;

    protected string $activityLogLabel = 'Consultation';

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'type',
        'topic',
        'question',
        'status',
        'answer',
        'preferred_datetime',
        'scheduled_datetime',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => ConsultationType::class,
            'status' => ConsultationStatus::class,
            'preferred_datetime' => 'datetime',
            'scheduled_datetime' => 'datetime',
            'answered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
