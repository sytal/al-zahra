<?php

namespace App\Support\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
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

    /**
     * Human-readable description, translated via lang/{locale}/activity_log.php.
     *
     * Lookup order: `activity_log.{key}.{resolvedEvent}` (e.g. a status
     * change resolved to a more specific event like "published" or
     * "status_answered"), then `activity_log.{key}.{eventName}`, then a
     * generic `activity_log.default.{eventName}` fallback, then a plain
     * untranslated sentence so nothing ever breaks for a model that has no
     * dedicated lang entries yet.
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        $key = $this->activityLogTranslationKey ?? Str::snake(class_basename($this));
        $resolvedEvent = $this->resolveActivityLogEvent($eventName);
        $params = $this->attributesForActivityLogDescription();

        foreach ([
            "activity_log.{$key}.{$resolvedEvent}",
            "activity_log.{$key}.{$eventName}",
            "activity_log.default.{$eventName}",
        ] as $translationKey) {
            if (Lang::has($translationKey)) {
                return __($translationKey, $params);
            }
        }

        $label = $this->activityLogLabel ?? class_basename($this);

        return match ($eventName) {
            'created' => "{$label} was created",
            'updated' => "{$label} was updated",
            'deleted' => "{$label} was deleted",
            default => "{$label} was {$eventName}",
        };
    }

    /**
     * Generically detects common "semantic" state transitions on update
     * (publish flags, status enums, completed_at/confirmed_at timestamps)
     * so models don't each need to override getDescriptionForEvent just to
     * get a more specific sentence than "was updated".
     */
    protected function resolveActivityLogEvent(string $eventName): string
    {
        if ($eventName !== 'updated') {
            return $eventName;
        }

        $old = $this->oldAttributes ?? [];

        if (array_key_exists('is_published', $old) && ! $old['is_published'] && $this->is_published) {
            return 'published';
        }

        if (array_key_exists('is_confirmed', $old) && ! $old['is_confirmed'] && $this->is_confirmed) {
            return 'confirmed';
        }

        if (array_key_exists('unsubscribed_at', $old) && ! $old['unsubscribed_at'] && $this->unsubscribed_at) {
            return 'unsubscribed';
        }

        if (array_key_exists('completed_at', $old) && ! $old['completed_at'] && $this->completed_at) {
            return 'completed';
        }

        if (array_key_exists('status', $old)) {
            $newStatus = $this->status instanceof \BackedEnum ? $this->status->value : $this->status;
            $oldStatus = $old['status'];

            if ($newStatus && $newStatus !== $oldStatus) {
                return "status_{$newStatus}";
            }
        }

        return 'updated';
    }

    /**
     * Translation parameters for the description. Override
     * `activityLogDescriptionAttributes()` per model to add more than the
     * default ":title" placeholder (e.g. ":course", ":lesson").
     */
    protected function attributesForActivityLogDescription(): array
    {
        return array_merge(
            [
                'title' => $this->activityLogSubjectLabel(),
                'label' => $this->activityLogLabel ?? class_basename($this),
            ],
            $this->activityLogDescriptionAttributes(),
        );
    }

    protected function activityLogDescriptionAttributes(): array
    {
        return [];
    }

    protected function activityLogSubjectLabel(): string
    {
        foreach (['title', 'name', 'email', 'key'] as $attribute) {
            $value = $this->getAttribute($attribute) ?? null;

            if ($value === null || $value === '') {
                continue;
            }

            return is_array($value)
                ? ($value[app()->getLocale()] ?? reset($value))
                : (string) $value;
        }

        return (string) $this->getKey();
    }
}
