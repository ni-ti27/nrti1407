<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
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
        Blade::directive('obfuscateEmail', function ($expression) {
            return "<?php
                \$email = (string) $expression;
                \$maskedEmail = str_replace(['@', '.', '-'], [' [at] ', ' [dot] ', '-'], \$email);
                echo '<span style=\"font-size: 1.3rem;\">' . \$maskedEmail . '</span>';
            ?>";
        });
    }
}