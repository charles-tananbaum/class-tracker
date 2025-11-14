<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\ClassController;
use App\Models\HbsClass;

// Health check route to verify database state
Route::get('/health', function () {
    try {
        $dbPath = config('database.connections.sqlite.database');
        $dbExists = file_exists($dbPath);
        $migrationsTable = Schema::hasTable('migrations');
        $classesTable = Schema::hasTable('classes');
        
        return response()->json([
            'status' => 'ok',
            'database_file_exists' => $dbExists,
            'database_path' => $dbPath,
            'migrations_table_exists' => $migrationsTable,
            'classes_table_exists' => $classesTable,
            'message' => $classesTable ? 'Database is ready' : 'Migrations need to be run'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/{class}/participation', [ClassController::class, 'checkParticipation'])->name('classes.check-participation');
    Route::post('/classes/{class}/participation', [ClassController::class, 'toggleParticipation'])->name('classes.toggle-participation');
    Route::post('/classes/{class}/grade', [ClassController::class, 'updateGrade'])->name('classes.update-grade');
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// Route model binding for HbsClass (filtered by authenticated user)
Route::bind('class', function ($value) {
    return HbsClass::where('user_id', auth()->id())
        ->findOrFail($value);
});
