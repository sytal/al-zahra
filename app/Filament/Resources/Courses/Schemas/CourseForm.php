<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Filament\Support\AdminEnum;
use App\Filament\Support\TranslatableTabs;
use App\Modules\Category\Models\Category;
use App\Support\Enums\CategoryType;
use App\Support\Enums\CourseAudience;
use App\Support\Enums\CourseLevel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
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
                        ->where('type', CategoryType::COURSE)
                        ->get()
                        ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                    ->searchable()
                    ->required(),

                Select::make('instructor_id')
                    ->label(__('admin_ui.l.instructor'))
                    ->relationship('instructor', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TranslatableTabs::make('short_description_tabs', ['short_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.short_description'))
                    ->rows(2)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                TranslatableTabs::make('full_description_tabs', ['full_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.full_description'))
                    ->rows(5)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                    ]),

                Section::make(__('admin_ui.l.details'))
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->columnSpanFull()
                    ->components([
                Select::make('level')
                    ->label(__('admin_ui.l.level'))
                    ->options(collect(CourseLevel::cases())->mapWithKeys(fn (CourseLevel $level) => [$level->value => AdminEnum::label($level)]))
                    ->required(),

                Select::make('audience')
                    ->label(__('admin_ui.l.audience'))
                    ->options(collect(CourseAudience::cases())->mapWithKeys(fn (CourseAudience $audience) => [$audience->value => AdminEnum::label($audience)]))
                    ->required(),

                TranslatableTabs::make('learning_outcomes_tabs', ['learning_outcomes'], fn (string $locale, string $field) => Repeater::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.learning_outcomes'))
                    ->simple(TextInput::make('outcome')->required())
                    ->default([])
                    ->addActionLabel(__('admin_ui.l.add_outcome'))),

                TextInput::make('estimated_duration_hours')
                    ->label(__('admin_ui.l.estimated_duration_hours'))
                    ->numeric()
                    ->minValue(0),

                    ]),

                Section::make(__('admin_ui.l.prerequisites'))
                    ->icon('heroicon-o-clipboard-document-check')
                    ->columnSpanFull()
                    ->components([
                Repeater::make('prerequisites')
                    ->label(__('admin_ui.l.prerequisites'))
                    ->simple(TextInput::make('prerequisite')->required())
                    ->default([])
                    ->addActionLabel(__('admin_ui.l.add_prerequisite')),
                    ]),

                Section::make(__('admin_ui.l.course_resources'))
                    ->icon('heroicon-o-paper-clip')
                    ->columnSpanFull()
                    ->components([
                Repeater::make('course_resources')
                    ->label(__('admin_ui.l.course_resources'))
                    ->schema([
                        TextInput::make('label')
                            ->label(__('admin_ui.l.label'))
                            ->required(),

                        Select::make('kind')
                            ->label(__('admin_ui.l.kind'))
                            ->options([
                                'file' => __('admin_ui.enum.file'),
                                'link' => __('admin_ui.enum.link'),
                                'note' => __('admin_ui.enum.note'),
                            ])
                            ->native(false)
                            ->live()
                            ->required(),

                        FileUpload::make('file')
                            ->label(__('admin_ui.l.resource_file'))
                            ->disk('public')
                            ->directory('uploads/courses/resources')
                            ->visible(fn (callable $get) => $get('kind') === 'file')
                            ->required(fn (callable $get) => $get('kind') === 'file'),

                        TextInput::make('url')
                            ->label(__('admin_ui.l.external_url'))
                            ->url()
                            ->visible(fn (callable $get) => $get('kind') === 'link')
                            ->required(fn (callable $get) => $get('kind') === 'link'),

                        Textarea::make('note')
                            ->label(__('admin_ui.l.note'))
                            ->rows(2)
                            ->visible(fn (callable $get) => $get('kind') === 'note')
                            ->required(fn (callable $get) => $get('kind') === 'note'),
                    ])
                    ->default([])
                    ->addActionLabel(__('admin_ui.l.add_resource')),
                    ]),

                Section::make(__('admin_ui.l.media'))
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->components([
                FileUpload::make('cover_image')
                    ->label(__('admin_ui.l.cover_image'))
                    ->image()
                    ->disk('public')
                    ->directory('uploads/courses')
                    ->imageEditor(),

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
