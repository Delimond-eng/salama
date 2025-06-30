<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         DB::statement("SET time_zone = '+01:00'");

         Blade::directive('active', function ($routes) {
            return "<?php
                \$active = '';
                foreach ($routes as \$route) {
                    if (Route::is(\$route)) {
                        \$active = 'side-menu--active';
                        break;
                    }
                }
                echo \$active;
            ?>";
        });
    }
}
