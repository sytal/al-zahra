<?php

namespace App\Filament\Concerns;

/**
 * Localized model / navigation labels for Filament resources.
 * The using resource declares `protected static string $labelKey` matching admin_ui.models.{key}.
 */
trait HasAdminNavigation
{
    public static function getModelLabel(): string
    {
        return __('admin_ui.models.'.static::$labelKey.'.one');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin_ui.models.'.static::$labelKey.'.many');
    }

    public static function getNavigationLabel(): string
    {
        return static::getPluralModelLabel();
    }
}
