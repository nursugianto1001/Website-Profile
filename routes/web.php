<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\BackgroundVideoController;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/facilities', [PublicController::class, 'facilities'])->name('facilities');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('facilities', FacilityController::class);

    Route::resource('background-videos', BackgroundVideoController::class);
    Route::post('background-videos/{backgroundVideo}/set-active', [BackgroundVideoController::class, 'setActive'])
        ->name('background-videos.set-active');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
