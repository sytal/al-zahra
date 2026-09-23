<?php

namespace App\Modules\Resource\Models;

use App\Modules\Category\Models\Category;
use App\Support\Enums\ResourceType;
use App\Support\Traits\HasActivityLog;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Resource extends Model implements HasMedia
{
    use HasActivityLog, HasTranslations, HasUuid, InteractsWithMedia, SoftDeletes;

    protected string $activityLogLabel = 'Resource';

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'resource_type',
        'is_free',
        'price',
        'download_count',
        'is_published',
        'meta_title',
        'meta_description',
    ];

    public array $translatable = [
        'title',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'resource_type' => ResourceType::class,
            'is_free' => 'boolean',
            'is_published' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('resource_file')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
