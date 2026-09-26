<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Spatie\Activitylog\Models\Activity;

class RecentActivityWidget extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 10;

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('activity.heading'))
            ->description(__('admin_ui.activity_desc'))
            ->striped()
            ->query(fn () => Activity::query()->with(['causer', 'subject'])->latest()->limit(10))
            ->paginated(false)
            ->emptyStateHeading(__('activity.empty'))
            ->columns([
                TextColumn::make('description')->label(__('activity.description'))->wrap(),
                TextColumn::make('causer.name')->label(__('activity.causer'))->default(__('activity.system')),
                TextColumn::make('subject_type')
                    ->label(__('activity.subject'))
                    ->formatStateUsing(fn (?string $state) => $state ? class_basename($state) : '-'),
                TextColumn::make('created_at')->label(__('activity.when'))->since(),
            ]);
    }
}
