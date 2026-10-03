<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use App\Filament\Support\TranslatableTabs;
use App\Modules\Course\Models\CourseBlock;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    private const BLOCK_TYPES = [
        'reading' => 'Reading',
        'practical_quiz' => 'Practical quiz',
        'case_study' => 'Case study',
        'research_reading' => 'Research reading',
        'discussion' => 'Discussion',
        'graded_quiz' => 'Graded quiz',
        'research_paper' => 'Research paper',
        'case_analysis' => 'Case analysis',
        'assignment' => 'Assignment',
        'research_activity' => 'Research activity',
    ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TranslatableTabs::make('title_tabs', ['title'], fn (string $locale, string $field) => TextInput::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.title'))
                    ->required($locale === config('app.fallback_locale', 'en'))
                    ->maxLength(255)),

                TranslatableTabs::make('description_tabs', ['description'], fn (string $locale, string $field) => Textarea::make("{$field}.{$locale}")
                    ->label(__('admin_ui.l.description'))
                    ->rows(2)),

                Repeater::make('blocks')
                    ->label(__('admin_ui.l.blocks'))
                    ->relationship('blocks')
                    ->reorderable('sort_order')
                    ->orderColumn('sort_order')
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['title']['en'] ?? __('admin_ui.l.block'))
                    ->addActionLabel(__('admin_ui.l.add_block'))
                    ->afterCreate(fn (array $data, CourseBlock $record) => static::syncAssignmentAttachments($data, $record))
                    ->afterUpdate(fn (array $data, CourseBlock $record) => static::syncAssignmentAttachments($data, $record))
                    ->schema([
                        TextInput::make('title.en')
                            ->label(__('admin_ui.l.title'))
                            ->required()
                            ->maxLength(255),

                        Select::make('type')
                            ->label(__('admin_ui.l.type'))
                            ->options(self::BLOCK_TYPES)
                            ->native(false)
                            ->live()
                            ->required(),

                        Toggle::make('is_preview')
                            ->label(__('admin_ui.l.free_preview')),

                        TextInput::make('estimated_minutes')
                            ->label(__('admin_ui.l.duration_minutes'))
                            ->numeric()
                            ->minValue(0),

                        // reading / research_reading
                        RichEditor::make('content.body')
                            ->label(__('admin_ui.l.body'))
                            ->visible(fn (callable $get) => in_array($get('type'), ['reading', 'research_reading'])),

                        // practical_quiz / graded_quiz
                        Repeater::make('content.questions')
                            ->label(__('admin_ui.l.questions'))
                            ->visible(fn (callable $get) => in_array($get('type'), ['practical_quiz', 'graded_quiz']))
                            ->schema([
                                TextInput::make('question')
                                    ->label(__('admin_ui.l.question'))
                                    ->required(),

                                Repeater::make('options')
                                    ->label(__('admin_ui.l.options'))
                                    ->schema([
                                        TextInput::make('text')
                                            ->label(__('admin_ui.l.option_text'))
                                            ->required(),

                                        Checkbox::make('is_correct')
                                            ->label(__('admin_ui.l.correct')),
                                    ])
                                    ->default([]),

                                Textarea::make('explanation')
                                    ->label(__('admin_ui.l.explanation'))
                                    ->rows(2),
                            ])
                            ->default([])
                            ->addActionLabel(__('admin_ui.l.add_question')),

                        TextInput::make('content.pass_percent')
                            ->label(__('admin_ui.l.pass_percent'))
                            ->numeric()
                            ->default(70)
                            ->visible(fn (callable $get) => $get('type') === 'graded_quiz'),

                        // case_study / research_paper / case_analysis
                        RichEditor::make('content.writeup')
                            ->label(__('admin_ui.l.writeup'))
                            ->visible(fn (callable $get) => in_array($get('type'), ['case_study', 'research_paper', 'case_analysis'])),

                        Repeater::make('content.links')
                            ->label(__('admin_ui.l.links'))
                            ->visible(fn (callable $get) => in_array($get('type'), ['case_study', 'research_paper', 'case_analysis']))
                            ->schema([
                                TextInput::make('url')
                                    ->label(__('admin_ui.l.external_url'))
                                    ->url()
                                    ->required(),

                                TextInput::make('reference')
                                    ->label(__('admin_ui.l.reference'))
                                    ->required(),
                            ])
                            ->default([])
                            ->addActionLabel(__('admin_ui.l.add_link')),

                        // discussion
                        Textarea::make('content.prompt')
                            ->label(__('admin_ui.l.prompt'))
                            ->rows(3)
                            ->visible(fn (callable $get) => $get('type') === 'discussion'),

                        // assignment
                        RichEditor::make('content.instructions')
                            ->label(__('admin_ui.l.instructions'))
                            ->visible(fn (callable $get) => $get('type') === 'assignment'),

                        FileUpload::make('content.files')
                            ->label(__('admin_ui.l.files'))
                            ->disk('public_media')
                            ->directory('uploads/courses/assignments')
                            ->multiple()
                            ->visible(fn (callable $get) => $get('type') === 'assignment'),

                        DatePicker::make('content.due_date')
                            ->label(__('admin_ui.l.due_date'))
                            ->visible(fn (callable $get) => $get('type') === 'assignment'),

                        // research_activity
                        RichEditor::make('content.activity_prompt')
                            ->label(__('admin_ui.l.prompt'))
                            ->visible(fn (callable $get) => $get('type') === 'research_activity'),

                        Toggle::make('content.requires_submission')
                            ->label(__('admin_ui.l.requires_submission'))
                            ->visible(fn (callable $get) => $get('type') === 'research_activity'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->formatStateUsing(fn ($record) => $record->getTranslation('title', app()->getLocale()))
                    ->searchable(),

                TextColumn::make('blocks_count')
                    ->label(__('admin_ui.l.blocks'))
                    ->counts('blocks'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Moves assignment task attachments uploaded via the plain
     * `content.files` FileUpload into the block's real Medialibrary
     * `attachments` collection (no filament/spatie-media-library plugin
     * needed — same manual addMediaFromDisk pattern as
     * App\Filament\Concerns\SavesMediaLibraryUploads, adapted for a
     * repeater relationship item instead of a Create/Edit page). Once
     * transferred, the paths are stripped from the `content` column so
     * resaving the form never re-adds the same files as duplicate media.
     */
    private static function syncAssignmentAttachments(array $data, CourseBlock $record): void
    {
        $paths = data_get($data, 'content.files', []);

        if (blank($paths) || ! is_array($paths)) {
            return;
        }

        foreach ($paths as $path) {
            if (! is_string($path) || ! Storage::disk('public_media')->exists($path)) {
                continue;
            }

            $record->addMediaFromDisk($path, 'public_media')->toMediaCollection('attachments');
        }

        $content = $record->content ?? [];
        unset($content['files']);
        $record->update(['content' => $content]);
    }
}
