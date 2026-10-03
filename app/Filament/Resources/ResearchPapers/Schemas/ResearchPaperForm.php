<?php

namespace App\Filament\Resources\ResearchPapers\Schemas;

use App\Filament\Support\TranslatableTabs;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use App\Support\Enums\FullPaperType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ResearchPaperForm
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
                        ->where('type', CategoryType::RESEARCH)
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

                TranslatableTabs::make('research_question_tabs', ['research_question'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.research_question'))
                    ->rows(3)),

                TranslatableTabs::make('findings_summary_tabs', ['findings_summary'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.findings_summary'))
                    ->rows(3)),

                TranslatableTabs::make('significance_tabs', ['significance'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.significance'))
                    ->rows(3)),

                    ]),

                Section::make(__('admin_ui.l.paper'))
                    ->icon('heroicon-o-document-text')
                    ->columnSpanFull()
                    ->components([
                Radio::make('full_paper_type')
                    ->label(__('admin_ui.l.full_paper_type'))
                    ->options([
                        FullPaperType::PDF_UPLOAD->value => 'PDF upload',
                        FullPaperType::EXTERNAL_LINK->value => 'External link',
                    ])
                    ->live()
                    ->default(FullPaperType::PDF_UPLOAD->value)
                    ->required(),

                FileUpload::make('paper_file')
                    ->label(__('admin_ui.l.paper_file_pdf'))
                    ->disk('public_media')
                    ->directory('uploads/research-papers')
                    ->acceptedFileTypes(['application/pdf'])
                    ->visible(fn (callable $get) => $get('full_paper_type') === FullPaperType::PDF_UPLOAD->value),

                TextInput::make('external_url')
                    ->label(__('admin_ui.l.external_url'))
                    ->url()
                    ->maxLength(255)
                    ->visible(fn (callable $get) => $get('full_paper_type') === FullPaperType::EXTERNAL_LINK->value),

                Repeater::make('co_authors')
                    ->label(__('admin_ui.l.co_authors'))
                    ->schema([
                        TextInput::make('name')->label(__('admin_ui.l.name'))->required(),
                        TextInput::make('affiliation')->label(__('admin_ui.l.affiliation')),
                    ])
                    ->columns(2)
                    ->default([])
                    ->addActionLabel(__('admin_ui.l.add_co_author')),

                TextInput::make('published_year')
                    ->label(__('admin_ui.l.published_year'))
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue((int) date('Y') + 1),

                    ]),

                Section::make(__('admin_ui.l.media'))
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->components([
                FileUpload::make('cover_image')
                    ->label(__('admin_ui.l.cover_image'))
                    ->image()
                    ->disk('public_media')
                    ->directory('uploads/research-papers/covers'),

                    ]),

                Section::make(__('admin_ui.l.publishing'))
                    ->icon('heroicon-o-paper-airplane')
                    ->columnSpanFull()
                    ->components([
                Toggle::make('is_published')
                    ->label(__('admin_ui.l.published'))
                    ->default(false),
                    ]),
            ]);
    }
}
