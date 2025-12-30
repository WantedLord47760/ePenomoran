<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Surat;
use App\Policies\SuratPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        // Register policies
        Gate::policy(Surat::class, SuratPolicy::class);

        // Share app settings globally to all views
        if (Schema::hasTable('settings')) {
            $settings = Setting::getAllCached();
            $defaults = Setting::getDefaults();

            // Merge with defaults (settings override defaults)
            $app_settings = array_merge($defaults, $settings);

            View::share('app_settings', $app_settings);
        } else {
            // Fallback if table doesn't exist yet
            View::share('app_settings', [
                'app_name' => 'E-Num',
                'app_logo' => null,
                'institution_name' => 'Dinas Kominfo Siak',
                'footer_text' => '© ' . date('Y') . ' E-Num - Sistem Penomoran Surat Digital',
            ]);
        }
    }
}
