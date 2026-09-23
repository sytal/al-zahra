<?php

namespace App\Filament\Resources\Resources\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use App\Support\Enums\ResourceType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ResourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('title_tabs', ['title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                    ->label('Title')
                    ->required($locale === config('app.fallback_locale', 'en'))
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $state, callable $set, callable $get, string $locale) {
                        if ($locale === 'en' && blank($get('slug'))) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->maxLength(255)),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Select::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::query()
                        ->where('type', CategoryType::RESOURCE)
                        ->get()
                        ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                    ->searchable()
                    ->required(),

                TranslatableTabs::make('description_tabs', ['description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Description')
                    ->rows(4)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                Select::make('resource_type')
                    ->label('Resource type')
                    ->options(collect(ResourceType::cases())->mapWithKeys(fn (ResourceType $type) => [$type->value => ucfirst($type->value)]))
                    ->required(),

                FileUpload::make('resource_file')
                    ->label('Resource file')
                    ->disk('public')
                    ->directory('uploads/resources/files')
                    ->required(),

                FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/resources/thumbnails'),

                Toggle::make('is_free')
                    ->label('Free')
                    ->live()
                    ->default(true),

                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix('USD')
                    ->visible(fn (callable $get) => ! $get('is_free'))
                    ->required(fn (callable $get) => ! $get('is_free')),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
            ]);
    }
}
