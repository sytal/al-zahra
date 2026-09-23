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
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ResearchPaperForm
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
                        ->where('type', CategoryType::RESEARCH)
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

                TranslatableTabs::make('research_question_tabs', ['research_question'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Research question')
                    ->rows(3)),

                TranslatableTabs::make('findings_summary_tabs', ['findings_summary'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Findings summary')
                    ->rows(3)),

                TranslatableTabs::make('significance_tabs', ['significance'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Significance')
                    ->rows(3)),

                Radio::make('full_paper_type')
                    ->label('Full paper type')
                    ->options([
                        FullPaperType::PDF_UPLOAD->value => 'PDF upload',
                        FullPaperType::EXTERNAL_LINK->value => 'External link',
                    ])
                    ->live()
                    ->default(FullPaperType::PDF_UPLOAD->value)
                    ->required(),

                FileUpload::make('paper_file')
                    ->label('Paper file (PDF)')
                    ->disk('public')
                    ->directory('uploads/research-papers')
                    ->acceptedFileTypes(['application/pdf'])
                    ->visible(fn (callable $get) => $get('full_paper_type') === FullPaperType::PDF_UPLOAD->value),

                TextInput::make('external_url')
                    ->label('External URL')
                    ->url()
                    ->maxLength(255)
                    ->visible(fn (callable $get) => $get('full_paper_type') === FullPaperType::EXTERNAL_LINK->value),

                Repeater::make('co_authors')
                    ->label('Co-authors')
                    ->schema([
                        TextInput::make('name')->label('Name')->required(),
                        TextInput::make('affiliation')->label('Affiliation'),
                    ])
                    ->columns(2)
                    ->default([])
                    ->addActionLabel('Add co-author'),

                TextInput::make('published_year')
                    ->label('Published year')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue((int) date('Y') + 1),

                FileUpload::make('cover_image')
                    ->label('Cover image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/research-papers/covers'),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
            ]);
    }
}
