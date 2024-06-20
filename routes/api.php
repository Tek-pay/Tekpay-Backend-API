<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PinController;

// Authentication Routes
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::post('/generate-otp', [OTPController::class, 'generateOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);


// Admin Routes
Route::middleware(['auth:admin'])->group(function () {
    // Admin can perform CRUD operations on users
    Route::get('/users', [UserController::class, 'index']); // List all users
    Route::post('/users', [UserController::class, 'store']); // Create a new user
    Route::get('/users/{user}', [UserController::class, 'show']); // View details of a user
    Route::put('/users/{user}', [UserController::class, 'update']); // Update a user
    Route::delete('/users/{user}', [UserController::class, 'destroy']); // Delete a user

    // Admin-specific routes
    Route::get('/admins', [AdminController::class, 'index']); // List all admins
    Route::post('/admins', [AdminController::class, 'store']); // Create a new admin
    Route::get('/admins/{admin}', [AdminController::class, 'show']); // View details of an admin
    Route::put('/admins/{admin}', [AdminController::class, 'update']); // Update an admin
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy']); // Delete an admin
});


// User Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Users can only perform CRUD operations on their own account
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [UserController::class, 'update']); // Update user's own account
    Route::delete('/user', [UserController::class, 'destroy']); // Delete user's own account

    Route::post('pay/airtime', [BillPaymentController::class, 'buyAirtime']);
    Route::post('pay/electricity', [BillPaymentController::class, 'payElectricityBill']);
    Route::post('verify/electricity', [BillPaymentController::class, 'verifyElectricityBill']);
    Route::post('pay/data', [BillPaymentController::class, 'buyData']);
    Route::post('pay/tv', [BillPaymentController::class, 'subscribeTV']);

    Route::post('/set-pin', [PinController::class, 'setPin']);
    Route::post('/verify-pin', [PinController::class, 'verifyPin']);
});
