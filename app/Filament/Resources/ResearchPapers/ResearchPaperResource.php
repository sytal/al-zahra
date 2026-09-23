<?php

namespace App\Filament\Resources\ResearchPapers;

use App\Filament\Resources\ResearchPapers\Pages\CreateResearchPaper;
use App\Filament\Resources\ResearchPapers\Pages\EditResearchPaper;
use App\Filament\Resources\ResearchPapers\Pages\ListResearchPapers;
use App\Filament\Resources\ResearchPapers\Schemas\ResearchPaperForm;
use App\Filament\Resources\ResearchPapers\Tables\ResearchPapersTable;
use App\Modules\Research\Models\ResearchPaper;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResearchPaperResource extends Resource
{
    protected static ?string $model = ResearchPaper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ResearchPaperForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResearchPapersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResearchPapers::route('/'),
            'create' => CreateResearchPaper::route('/create'),
            'edit' => EditResearchPaper::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
