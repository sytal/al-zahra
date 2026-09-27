<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Support\Enums\ContactMessageStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin_ui.l.message'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('admin_ui.l.name'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label(__('admin_ui.l.email'))
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('subject')
                            ->label(__('admin_ui.l.subject'))
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->label(__('admin_ui.l.message'))
                            ->columnSpanFull()
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label(__('admin_ui.l.status'))
                            ->options(collect(ContactMessageStatus::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->value)]))
                            ->required(),
                    ]),
            ]);
    }
}
