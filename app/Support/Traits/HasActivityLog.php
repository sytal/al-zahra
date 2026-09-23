<?php

namespace App\Support\Traits;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

trait HasActivityLog
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        $label = $this->activityLogLabel ?? class_basename($this);

        return match ($eventName) {
            'created' => "{$label} was created",
            'updated' => "{$label} was updated",
            'deleted' => "{$label} was deleted",
            default => "{$label} was {$eventName}",
        };
    }
}
