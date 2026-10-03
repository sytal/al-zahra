<?php

namespace App\Filament\Resources\SupportTickets\Schemas;

use App\Filament\Support\AdminEnum;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SupportTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('admin_ui.l.request'))
                    ->columns(2)
                    ->schema([
                        Placeholder::make('subject')
                            ->label(__('admin_ui.l.subject'))
                            ->content(fn ($record) => $record?->subject ?: '—'),
                        Placeholder::make('requester')
                            ->label(__('admin_ui.l.requester'))
                            ->content(fn ($record) => $record?->user?->name ?: $record?->user?->email ?: '—'),
                        Placeholder::make('created_at')
                            ->label(__('admin_ui.l.requested_at'))
                            ->content(fn ($record) => $record?->created_at?->toDayDateTimeString() ?? '—'),
                    ]),
                Section::make(__('admin_ui.l.thread'))
                    ->schema([
                        Placeholder::make('thread')
                            ->hiddenLabel()
                            ->content(fn ($record) => $record
                                ? new \Illuminate\Support\HtmlString(
                                    $record->messages->map(fn ($m) => '<div class="mb-3 rounded-lg border p-3"><p class="text-xs font-semibold opacity-70">'.e($m->author_id === $record->user_id ? __('admin_ui.l.requester') : __('admin_ui.l.staff')).' &middot; '.$m->created_at->toDayDateTimeString().'</p><p class="mt-1 whitespace-pre-line">'.e($m->body).'</p></div>')->implode('')
                                )
                                : ''),
                    ]),
                Section::make(__('admin_ui.l.response'))
                    ->schema([
                        Select::make('status')
                            ->label(__('admin_ui.l.status'))
                            ->options(['open' => AdminEnum::label('open'), 'answered' => AdminEnum::label('answered'), 'closed' => AdminEnum::label('closed')])
                            ->required(),
                    ]),
            ]);
    }
}
