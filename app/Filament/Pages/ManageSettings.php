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

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-settings';

    /** Translatable (per-locale array) keys. */
    private const TRANSLATED = [
        'site_name', 'site_tagline', 'footer_about_text', 'mission_text', 'vision_text', 'hero_heading', 'hero_subtext',
    ];

    private const PLAIN = [
        'contact_email', 'contact_phone', 'contact_hours', 'footer_links', 'social_links', 'testimonials', 'newsletter_enabled',
    ];

    private const DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function getTitle(): string
    {
        return __('admin_ui.settings.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin_ui.settings.title');
    }

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
        $values = Setting::query()->whereIn('key', [...self::TRANSLATED, ...self::PLAIN])->pluck('value', 'key');
        $empty = collect(config('app.locales'))->mapWithKeys(fn ($l) => [$l => ''])->all();

        $data = [];
        foreach (self::TRANSLATED as $key) {
            $stored = $values->get($key, []);
            $data[$key] = array_merge($empty, is_array($stored) ? $stored : ['en' => (string) $stored]);
        }

        $data['contact_email'] = $values->get('contact_email');
        $data['contact_phone'] = $values->get('contact_phone');
        $data['contact_hours'] = array_merge(array_fill_keys(self::DAYS, null), (array) $values->get('contact_hours', []));
        $data['footer_links'] = array_values(array_filter((array) $values->get('footer_links', []), fn ($l) => is_array($l) && isset($l['url'])));
        $data['social_links'] = (array) $values->get('social_links', []);
        $data['testimonials'] = array_values((array) $values->get('testimonials', []));
        $data['newsletter_enabled'] = (bool) $values->get('newsletter_enabled', true);

        $this->form->fill($data);
    }

    private static function perLocale(string $key, callable $factory): Tabs
    {
        return Tabs::make($key.'_tabs')
            ->tabs(collect(config('app.locales'))
                ->map(fn (string $locale) => Tab::make(strtoupper($locale))->schema([$factory($locale)]))
                ->all())
            ->columnSpanFull();
    }

    public function form(Schema $schema): Schema
    {
        $locales = config('app.locales');

        return $schema
            ->components([
                Tabs::make('settings')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('admin_ui.settings.brand'))
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Section::make(__('admin_ui.settings.brand'))
                                    ->description(__('admin_ui.settings.brand_help'))
                                    ->schema([
                                        self::perLocale('site_name', fn ($l) => TextInput::make("site_name.{$l}")->label(__('admin_ui.settings.site_name'))->maxLength(120)),
                                        self::perLocale('site_tagline', fn ($l) => TextInput::make("site_tagline.{$l}")->label(__('admin_ui.settings.tagline'))->maxLength(200)),
                                        Toggle::make('newsletter_enabled')->label(__('admin_ui.settings.newsletter_enabled')),
                                    ]),
                            ]),

                        Tab::make(__('admin_ui.settings.contact'))
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make(__('admin_ui.settings.contact'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('contact_email')->label(__('admin_ui.settings.email'))->email()->helperText(__('admin_ui.settings.email_help')),
                                        TextInput::make('contact_phone')->label(__('admin_ui.settings.phone'))->tel(),
                                    ]),
                                Section::make(__('admin_ui.settings.hours'))
                                    ->description(__('admin_ui.settings.hours_help'))
                                    ->columns(['default' => 1, 'sm' => 2, 'lg' => 4])
                                    ->schema(collect(self::DAYS)->map(fn ($d) => TextInput::make("contact_hours.{$d}")
                                        ->label(__('admin_ui.days.'.$d))
                                        ->placeholder('09:00 - 17:00'))->all()),
                                Section::make(__('admin_ui.settings.social'))
                                    ->schema([
                                        KeyValue::make('social_links')
                                            ->hiddenLabel()
                                            ->keyLabel(__('admin_ui.settings.platform'))
                                            ->valueLabel('URL')
                                            ->addActionLabel(__('admin_ui.settings.add_social')),
                                    ]),
                            ]),

                        Tab::make(__('admin_ui.settings.hero'))
                            ->icon('heroicon-o-home')
                            ->schema([
                                Section::make(__('admin_ui.settings.hero'))
                                    ->description(__('admin_ui.settings.hero_help'))
                                    ->schema([
                                        self::perLocale('hero_heading', fn ($l) => TextInput::make("hero_heading.{$l}")->label(__('admin_ui.settings.hero_heading'))->maxLength(160)),
                                        self::perLocale('hero_subtext', fn ($l) => Textarea::make("hero_subtext.{$l}")->label(__('admin_ui.settings.hero_subtext'))->rows(3)->maxLength(300)),
                                    ]),
                            ]),

                        Tab::make(__('admin_ui.settings.testimonials'))
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            ->schema([
                                Section::make(__('admin_ui.settings.testimonials'))
                                    ->description(__('admin_ui.settings.testimonials_help'))
                                    ->schema([
                                        Repeater::make('testimonials')
                                            ->hiddenLabel()
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state) => ($state['name']['en'] ?? '') ?: __('admin_ui.settings.testimonial'))
                                            ->addActionLabel(__('admin_ui.settings.add_testimonial'))
                                            ->reorderable()
                                            ->schema([
                                                Toggle::make('demo')->label(__('admin_ui.settings.demo'))->helperText(__('admin_ui.settings.demo_help')),
                                                Tabs::make('t_tabs')->columnSpanFull()->tabs(collect($locales)->map(fn (string $l) => Tab::make(strtoupper($l))->schema([
                                                    Textarea::make("quote.{$l}")->label(__('admin_ui.settings.quote'))->rows(3),
                                                    TextInput::make("name.{$l}")->label(__('admin_ui.settings.person_name')),
                                                    TextInput::make("role.{$l}")->label(__('admin_ui.settings.person_role')),
                                                ]))->all()),
                                            ]),
                                    ]),
                            ]),

                        Tab::make(__('admin_ui.settings.footer'))
                            ->icon('heroicon-o-link')
                            ->schema([
                                Section::make(__('admin_ui.settings.footer'))
                                    ->schema([
                                        self::perLocale('footer_about_text', fn ($l) => Textarea::make("footer_about_text.{$l}")->label(__('admin_ui.settings.about_text'))->rows(3)->maxLength(400)),
                                        Repeater::make('footer_links')
                                            ->label(__('admin_ui.settings.footer_links'))
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state) => ($state['label']['en'] ?? '') ?: ($state['url'] ?? ''))
                                            ->addActionLabel(__('admin_ui.settings.add_link'))
                                            ->schema([
                                                TextInput::make('url')->label('URL')->required()->helperText(__('admin_ui.settings.url_help')),
                                                Tabs::make('l_tabs')->columnSpanFull()->tabs(collect($locales)->map(fn (string $l) => Tab::make(strtoupper($l))->schema([
                                                    TextInput::make("label.{$l}")->label(__('admin_ui.settings.link_label')),
                                                ]))->all()),
                                            ]),
                                    ]),
                            ]),

                        Tab::make(__('admin_ui.settings.mission'))
                            ->icon('heroicon-o-flag')
                            ->schema([
                                Section::make(__('admin_ui.settings.mission'))
                                    ->schema([
                                        self::perLocale('mission_text', fn ($l) => Textarea::make("mission_text.{$l}")->label(__('admin_ui.settings.mission_label'))->rows(4)),
                                        self::perLocale('vision_text', fn ($l) => Textarea::make("vision_text.{$l}")->label(__('admin_ui.settings.vision_label'))->rows(4)),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ([...self::TRANSLATED, ...self::PLAIN] as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }
            $value = $data[$key];
            if ($key === 'testimonials' || $key === 'footer_links') {
                $value = array_values((array) $value);
            }
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Notification::make()
            ->title(__('admin_ui.settings.saved'))
            ->success()
            ->send();
    }
}
