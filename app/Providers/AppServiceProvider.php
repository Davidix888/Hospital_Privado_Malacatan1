<?php

namespace App\Providers;

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
        // Ensure consistent UTF-8 handling for accents and ñ across requests.
        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8');
            mb_regex_encoding('UTF-8');
        }

        ini_set('default_charset', 'UTF-8');
        setlocale(LC_CTYPE, 'es_GT.UTF-8', 'es_ES.UTF-8', 'es_MX.UTF-8', 'C.UTF-8');
    }
}
