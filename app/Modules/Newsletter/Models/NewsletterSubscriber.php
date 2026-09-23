<?php

namespace App\Modules\Newsletter\Models;

use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasUuid;

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
}
