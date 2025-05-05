<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FieldController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\Admin\AdminBookingController;
use App\Http\Controllers\API\Admin\AdminFieldController;
use App\Http\Controllers\API\Admin\AdminTransactionController;

// Public API
Route::get('/fields', [FieldController::class, 'index']);
Route::get('/fields/{field}', [FieldController::class, 'show']);
Route::get('/fields/{field}/available-slots', [FieldController::class, 'getAvailableSlots']);
Route::get('/fields/{field}/weekly-slots', [FieldController::class, 'getWeeklySlots']);

Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{booking}', [BookingController::class, 'show']);

Route::post('/transactions/notification', [TransactionController::class, 'handleNotification']);

// Admin API (dengan auth:sanctum)
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    // Booking
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show']);
    Route::post('/bookings', [AdminBookingController::class, 'store']);
    Route::put('/bookings/{booking}', [AdminBookingController::class, 'update']);
    Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel']);
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy']);

    // Field
    Route::get('/fields', [AdminFieldController::class, 'index']);
    Route::post('/fields', [AdminFieldController::class, 'store']);
    Route::get('/fields/{field}', [AdminFieldController::class, 'show']);
    Route::put('/fields/{field}', [AdminFieldController::class, 'update']);
    Route::delete('/fields/{field}', [AdminFieldController::class, 'destroy']);
    Route::put('/fields/{field}/toggle-status', [AdminFieldController::class, 'toggleStatus']);

    // Transaction
    Route::get('/transactions', [AdminTransactionController::class, 'index']);
    Route::get('/transactions/{transaction}', [AdminTransactionController::class, 'show']);
    Route::post('/transactions/manual', [AdminTransactionController::class, 'createManualTransaction']);
    Route::put('/transactions/{transaction}/status', [AdminTransactionController::class, 'updateStatus']);
    Route::delete('/transactions/{transaction}', [AdminTransactionController::class, 'destroy']);
    Route::get('/transactions/statistics', [AdminTransactionController::class, 'getStatistics']);
    Route::put('/transactions/{transaction}/verify-payment', [AdminTransactionController::class, 'verifyPaymentProof']);
});
