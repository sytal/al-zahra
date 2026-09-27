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
                    ->label(__('admin_ui.l.email'))
                    ->searchable(),
                TextColumn::make('locale')
                    ->label(__('admin_ui.l.locale'))
                    ->badge(),
                IconColumn::make('is_confirmed')
                    ->label(__('admin_ui.l.confirmed'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('admin_ui.l.subscribed_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_confirmed')
                    ->label(__('admin_ui.l.confirmed')),
            ])
            ->headerActions([
                Action::make('exportCsv')
                    ->label(__('admin_ui.l.export_to_csv'))
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
