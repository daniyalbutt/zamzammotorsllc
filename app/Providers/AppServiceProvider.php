<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Set the application timezone to Asia/Karachi
        date_default_timezone_set('Asia/Karachi');
        
        // Set timezone for Carbon instances
        Carbon::now()->timezone('Asia/Karachi');
        
        // Set timezone for all new Carbon instances
        config(['app.timezone' => 'Asia/Karachi']);
    }
}
