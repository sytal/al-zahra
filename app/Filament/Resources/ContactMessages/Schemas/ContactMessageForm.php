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
                Section::make('Message')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Email')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('subject')
                            ->label('Subject')
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('message')
                            ->label('Message')
                            ->columnSpanFull()
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false),
                        Select::make('status')
                            ->label('Status')
                            ->options(collect(ContactMessageStatus::cases())->mapWithKeys(fn ($case) => [$case->value => ucfirst($case->value)]))
                            ->required(),
                    ]),
            ]);
    }
}
