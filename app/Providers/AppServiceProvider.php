<?php

namespace App\Providers;

use App\Models\NewAsset;
use App\Models\ValAsset;
use Illuminate\View\View;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): View => view('components.footer-login'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn(): View => view('components.footer-sidebar'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn(): View => view('components.header-user'),
        );
    }
}
