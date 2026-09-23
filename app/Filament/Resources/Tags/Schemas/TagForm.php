<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),

                Tabs::make('Translations')
                    ->tabs(
                        collect(config('app.locales'))
                            ->map(fn (string $locale) => Tab::make(strtoupper($locale))
                                ->schema([
                                    TextInput::make("name.{$locale}")
                                        ->label('Name')
                                        ->required(fn () => $locale === config('app.fallback_locale'))
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function (string $locale, $state, $set, $get) {
                                            if ($locale === config('app.fallback_locale') && blank($get('slug'))) {
                                                $set('slug', Str::slug($state));
                                            }
                                        }),
                                ]))
                            ->all()
                    )
                    ->columnSpanFull(),
            ]);
    }
}
