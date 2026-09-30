<?php

namespace App\Modules\Setting\Models;

use App\Support\Traits\HasActivityLog;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;

class Setting extends Model
{
    use HasActivityLog {
        HasActivityLog::getActivitylogOptions as private baseActivitylogOptions;
    }

    protected string $activityLogLabel = 'Setting';

    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return $this->baseActivitylogOptions()->logOnly(['key', 'value']);
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return ['title' => $this->key];
    }
}
