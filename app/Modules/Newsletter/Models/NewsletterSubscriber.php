<?php

namespace App\Modules\Newsletter\Models;

use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;

class NewsletterSubscriber extends Model
{
    use HasActivityLog, HasUuid {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Newsletter subscriber';

    protected $fillable = [
        'email',
        'locale',
        'is_confirmed',
        'confirmed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_confirmed' => 'boolean',
            'confirmed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['email', 'locale', 'is_confirmed', 'unsubscribed_at']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return ['title' => $this->email];
    }
}
