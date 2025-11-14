<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;
use App\Models\HbsClass;

Route::get('/', [ClassController::class, 'index'])->name('classes.index');
Route::post('/classes/{class}/participation', [ClassController::class, 'toggleParticipation'])->name('classes.toggle-participation');
Route::post('/classes/{class}/grade', [ClassController::class, 'updateGrade'])->name('classes.update-grade');

// Route model binding for HbsClass
Route::bind('class', function ($value) {
    return HbsClass::findOrFail($value);
});
