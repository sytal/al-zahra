<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
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
                    ->unique(ignoreRecord: true)
                    ->helperText('Auto-generated from the English title, editable.'),

                Select::make('category_id')
                    ->label('Category')
                    ->options(fn () => Category::query()
                        ->where('type', CategoryType::ARTICLE)
                        ->get()
                        ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                    ->searchable()
                    ->required(),

                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TranslatableTabs::make('excerpt_tabs', ['excerpt'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Excerpt')
                    ->rows(3)
                    ->maxLength(500)),

                TranslatableTabs::make('body_tabs', ['body'], fn (string $locale, string $field) => RichEditor::make("{$field}.{$locale}")
                    ->label('Body')
                    ->columnSpanFull()),

                FileUpload::make('featured_image')
                    ->label('Featured image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/articles')
                    ->dehydrated()
                    ->imageEditor(),

                Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),

                DateTimePicker::make('published_at')
                    ->label('Published at'),

                Section::make('SEO')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        TranslatableTabs::make('meta_title_tabs', ['meta_title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                            ->label('Meta title')
                            ->maxLength(255)),

                        TranslatableTabs::make('meta_description_tabs', ['meta_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(500)),
                    ]),
            ]);
    }
}
