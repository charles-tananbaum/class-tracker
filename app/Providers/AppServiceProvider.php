<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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

        // Auto-run migrations if tables don't exist (fallback for deployment)
        try {
            if (config('database.default') === 'sqlite') {
                // Check if migrations table exists
                $migrationsTableExists = Schema::hasTable('migrations');
                
                // If migrations table doesn't exist, or classes table doesn't exist, run migrations
                if (!$migrationsTableExists || !Schema::hasTable('classes')) {
                    // Only run in non-console mode to avoid conflicts with artisan commands
                    if (!$this->app->runningInConsole()) {
                        Artisan::call('migrate', ['--force' => true]);
                        
                        // Seed if classes table is empty
                        if (Schema::hasTable('classes')) {
                            $classCount = DB::table('classes')->count();
                            if ($classCount === 0) {
                                Artisan::call('db:seed', ['--class' => 'ClassSeeder', '--force' => true]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail - migrations might be running via deployment commands
            // This is just a fallback
        }
    }
}
