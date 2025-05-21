<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\User;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Pages\Settings;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\PermissionResource;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Sky,
            ])
            // Discover resources and pages on panel, NOT plugin
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->plugin(
                \Hasnayeen\Themes\ThemesPlugin::make()
            )
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigationItems([
    NavigationItem::make('Users')
        ->url(fn (): string => UserResource::getUrl())
        ->icon('heroicon-o-users') // group of people icon, good for Users
        ->group('Settings')
        ->sort(1)
        ->visible(fn (): bool => auth()->user()->can('User List')),

    NavigationItem::make('Roles')
        ->url(fn (): string => RoleResource::getUrl())
        ->icon('heroicon-o-shield-check') // shield with check for roles (security/authorization)
        ->group('Settings')
        ->sort(2)
        ->visible(fn (): bool => auth()->user()->can('Role List')),

    NavigationItem::make('Permissions')
        ->url(fn (): string => PermissionResource::getUrl())
        ->icon('heroicon-o-lock-closed') // lock icon for permissions (access control)
        ->group('Settings')
        ->sort(3)
        ->visible(fn (): bool => auth()->user()->can('Permission List')),
])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\MonthlyPatientsChart::class,
                \App\Filament\Widgets\MonthlyDoctorsChart::class,
                \App\Filament\Widgets\MonthlyCaseReportsChart::class,
                \App\Filament\Widgets\MonthlyWhatsapplogChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
    public function boot(): void
    {
        FilamentIcon::register([
            'panels::topbar.global-search.field' => 'fas-magnifying-glass',
            'panels::sidebar.group.collapse-button' => view('icons.chevron-up'),
        ]);
    }
}
