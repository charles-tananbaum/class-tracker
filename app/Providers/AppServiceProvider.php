<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

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
        // Create SQLite database file if it doesn't exist
        if (config('database.default') === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');
            if ($databasePath && !file_exists($databasePath)) {
                $directory = dirname($databasePath);
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                touch($databasePath);
            }
        }
    }
}
