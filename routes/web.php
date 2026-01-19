<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [ActionController::class, 'index'])->name('index');
        Route::post('/check-in', [ActionController::class, 'checkIn'])->name('check-in');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

});

require __DIR__.'/auth.php';
