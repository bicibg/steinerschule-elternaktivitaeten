<?php

namespace App\Providers;

use App\Models\SchoolEvent;
use App\Models\Shift;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view) {
            $view->with('hasSchoolEvents', SchoolEvent::exists());
            $view->with('hasCalendarEntries', Shift::exists());
        });
    }
}
