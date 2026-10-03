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
                                        ->label(__('admin_ui.l.professional_title'))
                                        ->maxLength(255),
                                    TextInput::make("tagline.{$locale}")
                                        ->label(__('admin_ui.l.tagline'))
                                        ->maxLength(255),
                                    Textarea::make("bio_short.{$locale}")
                                        ->label(__('admin_ui.l.short_bio'))
                                        ->rows(3),
                                    RichEditor::make("bio_full.{$locale}")
                                        ->label(__('admin_ui.l.full_bio')),
                                    Repeater::make("research_interests.{$locale}")
                                        ->label(__('admin_ui.l.research_interests'))
                                        ->simple(
                                            TextInput::make('value')->required()
                                        )
                                        ->addActionLabel(__('admin_ui.l.add_research_interest')),
                                ]))
                            ->all()
                    )
                    ->columnSpanFull(),

                Section::make(__('admin_ui.l.credentials'))
                    ->schema([
                        Repeater::make('credentials')
                            ->simple(
                                TextInput::make('value')->required()
                            )
                            ->addActionLabel(__('admin_ui.l.add_credential')),
                    ]),

                Section::make(__('admin_ui.l.social_links'))
                    ->schema([
                        KeyValue::make('social_links')
                            ->keyLabel(__('admin_ui.l.platform'))
                            ->valueLabel('URL')
                            ->addActionLabel(__('admin_ui.l.add_social_link')),
                    ]),

                Section::make(__('admin_ui.l.media'))
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->image()
                            ->disk('public_media')
                            ->directory('temp-uploads')
                            ->visibility('public'),
                        FileUpload::make('cover_photo')
                            ->image()
                            ->disk('public_media')
                            ->directory('temp-uploads')
                            ->visibility('public'),
                    ]),

                Toggle::make('is_published')
                    ->label(__('admin_ui.l.published'))
                    ->default(false),
            ]);
    }
}
