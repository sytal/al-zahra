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
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['default' => 1, 'lg' => 3])
            ->components([
                Group::make([
                    Section::make(__('admin_ui.l.content'))
                        ->icon('heroicon-o-pencil-square')
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
                                ->label(__('admin_ui.l.slug'))
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->helperText(__('admin_ui.l.auto_generated_from_the_english_title_ed')),

                            TranslatableTabs::make('excerpt_tabs', ['excerpt'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                                ->label(__('admin_ui.l.excerpt'))
                                ->rows(3)
                                ->maxLength(500)
                                ->helperText(__('admin_ui.l.shown_on_cards_up_to_500_characters'))),

                            TranslatableTabs::make('body_tabs', ['body'], fn (string $locale, string $field) => RichEditor::make("{$field}.{$locale}")
                                ->label(__('admin_ui.l.body'))
                                ->columnSpanFull()),
                        ]),

                    Section::make(__('admin_ui.l.seo'))
                        ->icon('heroicon-o-magnifying-glass')
                        ->collapsible()
                        ->collapsed()
                        ->components([
                            TranslatableTabs::make('meta_title_tabs', ['meta_title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                                ->label(__('admin_ui.l.meta_title'))
                                ->maxLength(255)
                                ->helperText(__('admin_ui.l.ideal_length_up_to_60_characters'))),

                            TranslatableTabs::make('meta_description_tabs', ['meta_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                                ->label(__('admin_ui.l.meta_description'))
                                ->rows(2)
                                ->maxLength(500)
                                ->helperText(__('admin_ui.l.ideal_length_up_to_160_characters'))),
                        ]),
                ])->columnSpan(['lg' => 2]),

                Group::make([
                    Section::make(__('admin_ui.l.publishing'))
                        ->icon('heroicon-o-paper-airplane')
                        ->components([
                            Toggle::make('is_published')
                                ->label(__('admin_ui.l.published'))
                                ->default(false),

                            DateTimePicker::make('published_at')
                                ->label(__('admin_ui.l.published_at')),
                        ]),

                    Section::make(__('admin_ui.l.media'))
                        ->icon('heroicon-o-photo')
                        ->components([
                            FileUpload::make('featured_image')
                                ->label(__('admin_ui.l.featured_image'))
                                ->image()
                                ->disk('public_media')
                                ->directory('uploads/articles')
                                ->dehydrated()
                                ->imageEditor()
                                ->imagePreviewHeight('160'),
                        ]),

                    Section::make(__('admin_ui.l.organization'))
                        ->icon('heroicon-o-folder')
                        ->components([
                            Select::make('category_id')
                                ->label(__('admin_ui.l.category'))
                                ->options(fn () => Category::query()
                                    ->where('type', CategoryType::ARTICLE)
                                    ->get()
                                    ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                                ->searchable()
                                ->required(),

                            Select::make('author_id')
                                ->label(__('admin_ui.l.author'))
                                ->relationship('author', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('tags')
                                ->label(__('admin_ui.l.tags'))
                                ->relationship('tags', 'name')
                                ->getOptionLabelFromRecordUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                                ->multiple()
                                ->searchable()
                                ->preload(),
                        ]),
                ])->columnSpan(['lg' => 1]),
            ]);
    }
}
