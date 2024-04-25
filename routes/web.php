<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
     * User Routes
     */
    Route::get('/people', [\App\Http\Controllers\PeopleController::class, 'index'])->name('people.index');
    Route::post('/people/upload', [\App\Http\Controllers\PeopleController::class, 'upload'])->name('people.upload');
    Route::get('/people/export', [\App\Http\Controllers\PeopleController::class, 'export'])->name('people.export');
});

require __DIR__.'/auth.php';
