<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Http\Middleware\SetAdminLocale;
use Filament\Tables\Table;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Foundation\Vite;
use Filament\Enums\ThemeMode;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    /**
     * @return array<int|string, string>
     */
    private function palette(string $name): array
    {
        return collect(config("theme.filament.$name", []))
            ->map(fn (string $triplet) => "rgb({$triplet})")
            ->all();
    }

    public function boot(): void
    {
        Table::configureUsing(fn (Table $table) => $table
            ->striped()
            ->paginationPageOptions([10, 25, 50])
            ->emptyStateHeading(fn () => __('admin_ui.l.no_records'))
            ->emptyStateDescription(fn () => __('admin_ui.l.no_records_hint')));
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName(fn () => __('admin_ui.brand'))
            ->brandLogo(asset('images/brand/logo-horizontal.svg'))
            ->darkModeBrandLogo(asset('images/brand/logo-horizontal-dark.svg'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('favicon.svg'))
            ->colors([
                'primary' => $this->palette('primary'),
                'gray' => $this->palette('neutral'),
                'warning' => $this->palette('secondary'),
                'success' => $this->palette('success'),
                'danger' => $this->palette('danger'),
                'info' => $this->palette('info'),
            ])
            ->font('Figtree', provider: LocalFontProvider::class)
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => app(Vite::class)('resources/css/filament/admin/theme.css'))
            ->darkMode()
            ->defaultThemeMode(ThemeMode::System)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->globalSearch()
            ->navigationGroups([
                NavigationGroup::make('Content')->label(fn () => __('admin_ui.nav_content'))->icon('heroicon-o-newspaper'),
                NavigationGroup::make('People')->label(fn () => __('admin_ui.nav_people'))->icon('heroicon-o-users'),
                NavigationGroup::make('Engagement')->label(fn () => __('admin_ui.nav_engagement'))->icon('heroicon-o-chat-bubble-left-right'),
                NavigationGroup::make('Settings')->label(fn () => __('admin_ui.nav_settings'))->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([AccountWidget::class])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetAdminLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
