<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use App\Modules\Course\Models\CourseBlockProgress;
use App\Modules\Course\Services\AssignmentReviewService;
use Filament\Actions\Action;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

/**
 * Admin screen listing submitted assignment blocks for a single course so
 * Pass/Needs-revision can be marked (docs/COURSE-BUILDER-DEV-PLAN.md Phase
 * 7/8). Deliberately a standalone resource Page + Table rather than a full
 * CRUD resource — review is the only action needed here.
 */
class ReviewAssignments extends Page implements HasTable
{
    use Concerns\InteractsWithCourseRecord;
    use InteractsWithTable;

    protected static string $resource = CourseResource::class;

    public function getTitle(): string
    {
        return __('admin_ui.l.review_assignments');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            EmbeddedTable::make(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                CourseBlockProgress::query()
                    ->whereHas('media', fn ($query) => $query->where('collection_name', 'submission'))
                    ->whereHas('block', fn ($query) => $query->where('type', 'assignment'))
                    ->whereHas('block.module', fn ($query) => $query->where('course_id', $this->record->id))
            )
            ->columns([
                TextColumn::make('enrollment.user.name')
                    ->label(__('admin_ui.l.student')),

                TextColumn::make('block.title')
                    ->label(__('admin_ui.l.block'))
                    ->formatStateUsing(fn ($record) => $record->block?->getTranslation('title', app()->getLocale())),

                TextColumn::make('updated_at')
                    ->label(__('admin_ui.l.submitted_at'))
                    ->dateTime(),

                TextColumn::make('mark')
                    ->label(__('admin_ui.l.mark'))
                    ->state(fn ($record) => $record->data['mark'] ?? '-')
                    ->badge(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([
                Action::make('download')
                    ->label(__('admin_ui.l.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->getFirstMediaUrl('submission'), shouldOpenInNewTab: true)
                    ->visible(fn ($record) => $record->getFirstMedia('submission') !== null),

                Action::make('mark')
                    ->label(__('admin_ui.l.mark'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->schema([
                        Radio::make('mark')
                            ->label(__('admin_ui.l.mark'))
                            ->options([
                                'pass' => __('admin_ui.l.pass'),
                                'needs_revision' => __('admin_ui.l.needs_revision'),
                            ])
                            ->required(),

                        Textarea::make('comment')
                            ->label(__('admin_ui.l.comment'))
                            ->rows(3),
                    ])
                    ->action(function (CourseBlockProgress $record, array $data) {
                        app(AssignmentReviewService::class)->mark($record, $data['mark'], $data['comment'] ?? null);

                        Notification::make()
                            ->title(__('admin_ui.l.assignment_marked'))
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
