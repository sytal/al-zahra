<?php

namespace App\Filament\Resources\Consultations;

use App\Filament\Resources\Consultations\Pages\EditConsultation;
use App\Filament\Resources\Consultations\Pages\ListConsultations;
use App\Filament\Resources\Consultations\Schemas\ConsultationForm;
use App\Filament\Resources\Consultations\Tables\ConsultationsTable;
use App\Modules\Consultation\Models\Consultation;
use App\Filament\Concerns\HasAdminNavigation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    use HasAdminNavigation;

    protected static string $labelKey = 'consultation';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Engagement';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', \App\Support\Enums\ConsultationStatus::PENDING)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return ConsultationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationsTable::configure($table);
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
            'index' => ListConsultations::route('/'),
            'edit' => EditConsultation::route('/{record}/edit'),
        ];
    }
}
