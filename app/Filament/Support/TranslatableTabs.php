<?php

namespace App\Filament\Support;

use Closure;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class TranslatableTabs
{
    /**
     * Build a locale-tabbed group of fields for a Spatie-translatable attribute.
     *
     * @param  array<string>  $fields  base state-path names, e.g. ['title'] or ['research_question', 'findings_summary']
     * @param  Closure(string $locale, string $field): \Filament\Schemas\Components\Component  $fieldFactory
     */
    public static function make(string $key, array $fields, Closure $fieldFactory): Tabs
    {
        $locales = config('app.locales', ['en']);

        return Tabs::make($key)
            ->tabs(collect($locales)
                ->map(function (string $locale) use ($fields, $fieldFactory) {
                    return Tab::make(strtoupper($locale))
                        ->schema(collect($fields)
                            ->map(fn (string $field) => $fieldFactory($locale, $field))
                            ->all());
                })
                ->all())
            ->columnSpanFull();
    }
}
