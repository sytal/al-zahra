<?php

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use App\Modules\Newsletter\Models\NewsletterSubscriber;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('locale')
                    ->label('Locale')
                    ->badge(),
                IconColumn::make('is_confirmed')
                    ->label('Confirmed')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Subscribed At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_confirmed')
                    ->label('Confirmed'),
            ])
            ->headerActions([
                Action::make('exportCsv')
                    ->label('Export to CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $subscribers = NewsletterSubscriber::query()->get();

                        $csv = "Email,Locale,Confirmed,Date\n";

                        foreach ($subscribers as $subscriber) {
                            $csv .= sprintf(
                                "%s,%s,%s,%s\n",
                                $subscriber->email,
                                $subscriber->locale,
                                $subscriber->is_confirmed ? 'Yes' : 'No',
                                $subscriber->created_at?->toDateTimeString()
                            );
                        }

                        return Response::streamDownload(
                            fn () => print ($csv),
                            'newsletter-subscribers-'.now()->format('Y-m-d').'.csv',
                            ['Content-Type' => 'text/csv']
                        );
                    }),
            ]);
    }
}
