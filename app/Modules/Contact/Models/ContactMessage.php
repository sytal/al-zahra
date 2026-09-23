<?php

namespace App\Modules\Contact\Models;

use App\Support\Enums\ContactMessageStatus;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use HasActivityLog, HasUuid, SoftDeletes;

    protected string $activityLogLabel = 'Contact message';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContactMessageStatus::class,
        ];
    }
}
