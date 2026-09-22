<?php

namespace App\Modules\Category\Models;

use App\Support\Enums\CategoryType;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasUuid, HasTranslations, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    public array $translatable = [
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'type' => CategoryType::class,
        ];
    }
}
