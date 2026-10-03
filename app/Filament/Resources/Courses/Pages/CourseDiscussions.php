<?php

namespace App\Filament\Resources\Courses\Pages;

use App\Filament\Resources\Courses\CourseResource;
use App\Modules\Course\Models\CourseDiscussionReply;
use App\Modules\Course\Services\CourseDiscussionAdminService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

/**
 * Admin screen listing student-started discussion threads for a single
 * course so an admin/director can reply (docs/COURSE-BUILDER-DEV-PLAN.md
 * Phase 7/8). Standalone Page + Table, not a full resource — reply is the
 * only action needed.
 */
class CourseDiscussions extends Page implements HasTable
{
    use Concerns\InteractsWithCourseRecord;
    use InteractsWithTable;

    protected static string $resource = CourseResource::class;

    public function getTitle(): string
    {
        return __('admin_ui.l.course_discussions');
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
                CourseDiscussionReply::query()
                    ->whereColumn('author_id', 'user_id')
                    ->whereHas('block.module', fn ($query) => $query->where('course_id', $this->record->id))
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('admin_ui.l.student')),

                TextColumn::make('block.title')
                    ->label(__('admin_ui.l.block'))
                    ->formatStateUsing(fn ($record) => $record->block?->getTranslation('title', app()->getLocale())),

                TextColumn::make('body')
                    ->label(__('admin_ui.l.message'))
                    ->limit(80),

                TextColumn::make('created_at')
                    ->label(__('admin_ui.l.submitted_at'))
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                Action::make('reply')
                    ->label(__('admin_ui.l.reply'))
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->schema([
                        Textarea::make('body')
                            ->label(__('admin_ui.l.reply'))
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (CourseDiscussionReply $record, array $data) {
                        app(CourseDiscussionAdminService::class)->reply($record, Auth::user(), $data['body']);

                        Notification::make()
                            ->title(__('admin_ui.l.reply_posted'))
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
