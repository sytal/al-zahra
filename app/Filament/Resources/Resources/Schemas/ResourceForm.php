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
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ResourceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin_ui.l.content'))
                    ->icon('heroicon-o-pencil-square')
                    ->columnSpanFull()
                    ->components([
                TranslatableTabs::make('title_tabs', ['title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.title'))
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
                    ->label(__('admin_ui.l.category'))
                    ->options(fn () => Category::query()
                        ->where('type', CategoryType::RESOURCE)
                        ->get()
                        ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                    ->searchable()
                    ->required(),

                TranslatableTabs::make('description_tabs', ['description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.description'))
                    ->rows(4)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                    ]),

                Section::make(__('admin_ui.l.file'))
                    ->icon('heroicon-o-document-arrow-down')
                    ->columnSpanFull()
                    ->components([
                Select::make('resource_type')
                    ->label(__('admin_ui.l.resource_type'))
                    ->options(collect(ResourceType::cases())->mapWithKeys(fn (ResourceType $type) => [$type->value => ucfirst($type->value)]))
                    ->required(),

                FileUpload::make('resource_file')
                    ->label(__('admin_ui.l.resource_file'))
                    ->disk('public')
                    ->directory('uploads/resources/files')
                    ->required(),

                FileUpload::make('thumbnail')
                    ->label(__('admin_ui.l.thumbnail'))
                    ->image()
                    ->disk('public')
                    ->directory('uploads/resources/thumbnails'),

                    ]),

                Section::make(__('admin_ui.l.publishing'))
                    ->icon('heroicon-o-paper-airplane')
                    ->columnSpanFull()
                    ->components([
                Toggle::make('is_free')
                    ->label(__('admin_ui.l.free'))
                    ->live()
                    ->default(true),

                TextInput::make('price')
                    ->label(__('admin_ui.l.price'))
                    ->numeric()
                    ->prefix('USD')
                    ->visible(fn (callable $get) => ! $get('is_free'))
                    ->required(fn (callable $get) => ! $get('is_free')),

                Toggle::make('is_published')
                    ->label(__('admin_ui.l.published'))
                    ->default(false),
                    ]),
            ]);
    }
}
