<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BackgroundVideoController;
use App\Http\Controllers\Web\BookingPageController;
use App\Http\Controllers\API\BookingController;

// Public Routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/facilities', [PublicController::class, 'facilities'])->name('facilities');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');

// BookingPageController Routes
Route::get('/browse', [BookingPageController::class, 'index'])->name('booking.home');
Route::get('/fields', [BookingPageController::class, 'fields'])->name('booking.fields');
Route::get('/fields/{field}', [BookingPageController::class, 'fieldDetail'])->name('booking.field-detail');
Route::get('/fields/{field}/book', [BookingPageController::class, 'bookingForm'])->name('booking.form');
Route::post('/booking/process', [BookingPageController::class, 'processBooking'])->name('booking.process');
Route::get('/booking/{booking}/success', [BookingPageController::class, 'bookingSuccess'])->name('booking.success');
Route::get('/payment/finish', [BookingPageController::class, 'finishPayment'])->name('payment.finish');
Route::get('/payment/unfinish', [BookingPageController::class, 'unfinishPayment'])->name('payment.unfinish');
Route::get('/payment/error', [BookingPageController::class, 'errorPayment'])->name('payment.error');

// Booking (hanya penampil)
Route::get('/bookings/{fieldId}', [BookingController::class, 'index'])->name('bookings.index');

// Auth Routes
require __DIR__ . '/auth.php';

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('facilities', FacilityController::class);
    Route::resource('background-videos', BackgroundVideoController::class);
    Route::post('background-videos/{backgroundVideo}/set-active', [BackgroundVideoController::class, 'setActive'])
        ->name('background-videos.set-active');

    // Gallery routes
    Route::resource('gallery', GalleryController::class);
    Route::post('gallery/{gallery}/toggle-featured', [GalleryController::class, 'toggleFeatured'])
        ->name('gallery.toggle-featured');

    Route::resource('background-videos', BackgroundVideoController::class);
    Route::post('background-videos/{backgroundVideo}/set-active', [BackgroundVideoController::class, 'setActive'])
        ->name('background-videos.set-active');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
