<?php

namespace App\Modules\Director\Models;

use App\Models\User;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Director extends Model
{
    use HasUuid, HasTranslations, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'professional_title',
        'tagline',
        'bio_short',
        'bio_full',
        'credentials',
        'research_interests',
        'social_links',
        'is_published',
    ];

    public array $translatable = [
        'professional_title',
        'tagline',
        'bio_short',
        'bio_full',
        'research_interests',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'social_links' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
