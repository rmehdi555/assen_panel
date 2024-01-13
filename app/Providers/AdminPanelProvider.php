<?php

namespace App\Providers;

use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->brandName('پنل مدیریت آسن')
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => [
                    100 => '#fff7ed',
                    200 => '#ffedd5',
                    300 => '#fed7aa',
                    400 => '#fb923c',
                    500 => '#fb923c',
                    600 => '#ea580c',
                    700 => '#c2410c',
                    800 => '#9a3412',
                    900 => '#7c2d12',
                    950 => '#431407',
                ],
                'secondary' => Color::Amber,
                'grey' => Color::Gray,
            ])
            ->font(
                family: 'iransans',
                url: asset('assets/fonts/iransans/_iransans.css'),
                provider: LocalFontProvider::class,
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->favicon(asset('assets/assen-logo.png'))
            ->sidebarCollapsibleOnDesktop()
            ->pages([

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth('full')
            ->navigationGroups([
                'لاجیستیک',
                'جستجو',
                'کنسلی ها',
                'مالی',
                'مدیریت کاربران',
                'محتوا',
                'تنظیمات',
            ]);
    }
}
