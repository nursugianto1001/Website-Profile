<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/menu', [PublicController::class, 'menu'])->name('menu');
Route::get('/outlets', [PublicController::class, 'outlets'])->name('outlets');
Route::get('/facilities', [PublicController::class, 'facilities'])->name('facilities');
Route::get('/careers', [PublicController::class, 'careers'])->name('careers');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    
    Route::resource('menus', MenuController::class);
    
    Route::resource('outlets', OutletController::class);
    
    Route::resource('facilities', FacilityController::class);

    Route::resource('careers', CareerController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
