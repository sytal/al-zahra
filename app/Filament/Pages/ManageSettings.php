<?php

namespace App\Filament\Pages;

use App\Modules\Setting\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $title = 'Settings';

    protected static ?string $navigationLabel = 'Settings';

    protected string $view = 'filament.pages.manage-settings';

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('settings.manage') ?? false;
    }

    public function mount(): void
    {
        $keys = [
            'site_name',
            'site_tagline',
            'contact_email',
            'contact_phone',
            'footer_about_text',
            'footer_links',
            'social_links',
            'mission_text',
            'vision_text',
            'newsletter_enabled',
        ];

        $values = Setting::query()->whereIn('key', $keys)->pluck('value', 'key');

        $this->form->fill([
            'site_name' => $values->get('site_name', collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all()),
            'site_tagline' => $values->get('site_tagline', collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all()),
            'contact_email' => $values->get('contact_email'),
            'contact_phone' => $values->get('contact_phone'),
            'footer_about_text' => $values->get('footer_about_text', collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all()),
            'footer_links' => $values->get('footer_links', []),
            'social_links' => $values->get('social_links', []),
            'mission_text' => $values->get('mission_text', collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all()),
            'vision_text' => $values->get('vision_text', collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all()),
            'newsletter_enabled' => (bool) $values->get('newsletter_enabled', true),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->schema([
                        Tabs::make('Site name translations')
                            ->tabs(
                                collect(config('app.locales'))
                                    ->map(fn (string $locale) => Tab::make(strtoupper($locale))
                                        ->schema([
                                            TextInput::make("site_name.{$locale}")
                                                ->label('Site name'),
                                            TextInput::make("site_tagline.{$locale}")
                                                ->label('Tagline'),
                                        ]))
                                    ->all()
                            )
                            ->columnSpanFull(),
                        TextInput::make('contact_email')
                            ->label('Contact email')
                            ->email(),
                        TextInput::make('contact_phone')
                            ->label('Contact phone'),
                    ]),

                Section::make('Footer')
                    ->schema([
                        Repeater::make('footer_links')
                            ->label('Footer links')
                            ->simple(
                                TextInput::make('value')->required()
                            )
                            ->addActionLabel('Add footer link'),
                        Tabs::make('Footer about translations')
                            ->tabs(
                                collect(config('app.locales'))
                                    ->map(fn (string $locale) => Tab::make(strtoupper($locale))
                                        ->schema([
                                            Textarea::make("footer_about_text.{$locale}")
                                                ->label('About text')
                                                ->rows(3),
                                        ]))
                                    ->all()
                            )
                            ->columnSpanFull(),
                    ]),

                Section::make('Mission / Vision')
                    ->schema([
                        Tabs::make('Mission/vision translations')
                            ->tabs(
                                collect(config('app.locales'))
                                    ->map(fn (string $locale) => Tab::make(strtoupper($locale))
                                        ->schema([
                                            Textarea::make("mission_text.{$locale}")
                                                ->label('Mission')
                                                ->rows(3),
                                            Textarea::make("vision_text.{$locale}")
                                                ->label('Vision')
                                                ->rows(3),
                                        ]))
                                    ->all()
                            )
                            ->columnSpanFull(),
                    ]),

                Section::make('Social Links')
                    ->schema([
                        KeyValue::make('social_links')
                            ->keyLabel('Platform')
                            ->valueLabel('URL')
                            ->addActionLabel('Add social link'),
                        Toggle::make('newsletter_enabled')
                            ->label('Newsletter enabled'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

}
