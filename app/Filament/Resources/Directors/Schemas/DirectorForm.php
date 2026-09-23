<?php

namespace App\Filament\Resources\Directors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class DirectorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Tabs::make('Translations')
                    ->tabs(
                        collect(config('app.locales'))
                            ->map(fn (string $locale) => Tab::make(strtoupper($locale))
                                ->schema([
                                    TextInput::make("professional_title.{$locale}")
                                        ->label('Professional title')
                                        ->maxLength(255),
                                    TextInput::make("tagline.{$locale}")
                                        ->label('Tagline')
                                        ->maxLength(255),
                                    Textarea::make("bio_short.{$locale}")
                                        ->label('Short bio')
                                        ->rows(3),
                                    RichEditor::make("bio_full.{$locale}")
                                        ->label('Full bio'),
                                    Repeater::make("research_interests.{$locale}")
                                        ->label('Research interests')
                                        ->simple(
                                            TextInput::make('value')->required()
                                        )
                                        ->addActionLabel('Add research interest'),
                                ]))
                            ->all()
                    )
                    ->columnSpanFull(),

                Section::make('Credentials')
                    ->schema([
                        Repeater::make('credentials')
                            ->simple(
                                TextInput::make('value')->required()
                            )
                            ->addActionLabel('Add credential'),
                    ]),

                Section::make('Social links')
                    ->schema([
                        KeyValue::make('social_links')
                            ->keyLabel('Platform')
                            ->valueLabel('URL')
                            ->addActionLabel('Add social link'),
                    ]),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->image()
                            ->disk(config('filesystems.default'))
                            ->directory('temp-uploads')
                            ->visibility('public'),
                        FileUpload::make('cover_photo')
                            ->image()
                            ->disk(config('filesystems.default'))
                            ->directory('temp-uploads')
                            ->visibility('public'),
                    ]),

                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
            ]);
    }
}
