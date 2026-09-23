<?php

namespace App\Filament\Resources\Courses\Schemas;

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
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CourseForm
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
                        ->where('type', CategoryType::COURSE)
                        ->get()
                        ->mapWithKeys(fn (Category $category) => [$category->id => $category->getTranslation('name', app()->getLocale())]))
                    ->searchable()
                    ->required(),

                Select::make('instructor_id')
                    ->label('Instructor')
                    ->relationship('instructor', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TranslatableTabs::make('short_description_tabs', ['short_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Short description')
                    ->rows(2)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                TranslatableTabs::make('full_description_tabs', ['full_description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label('Full description')
                    ->rows(5)
                    ->required($locale === config('app.fallback_locale', 'en'))),

                Select::make('level')
                    ->label('Level')
                    ->options(collect(CourseLevel::cases())->mapWithKeys(fn (CourseLevel $level) => [$level->value => ucfirst($level->value)]))
                    ->required(),

                Select::make('audience')
                    ->label('Audience')
                    ->options(collect(CourseAudience::cases())->mapWithKeys(fn (CourseAudience $audience) => [$audience->value => ucfirst($audience->value)]))
                    ->required(),

                TranslatableTabs::make('learning_outcomes_tabs', ['learning_outcomes'], fn (string $locale, string $field) => Repeater::make("{$field}.{$locale}")
                    ->label('Learning outcomes')
                    ->simple(TextInput::make('outcome')->required())
                    ->default([])
                    ->addActionLabel('Add outcome')),

                TextInput::make('estimated_duration_hours')
                    ->label('Estimated duration (hours)')
                    ->numeric()
                    ->minValue(0),

                FileUpload::make('cover_image')
                    ->label('Cover image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads/courses')
                    ->imageEditor(),

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
