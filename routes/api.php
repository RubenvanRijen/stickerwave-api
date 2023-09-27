<?php

use App\Http\Controllers\JwtAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(
    function ($router) {
        Route::post('/login', [JwtAuthController::class, 'login']);
        Route::post('/register', [JwtAuthController::class, 'register']);
        Route::post('/logout', [JwtAuthController::class, 'logout'])->middleware('jwt');
        Route::post('/refresh', [JwtAuthController::class, 'refresh'])->middleware('jwt');
        Route::get('/user-profile', [JwtAuthController::class, 'getCurrentUser'])->middleware('jwt');

        Route::post('/send-verify-email', [JwtAuthController::class, 'sendEmailVerification']);
        Route::post('/resend-verification', [JwtAuthController::class, 'createNewVerificationLink']);
        Route::post('/verify-email/{verification_token}', [JwtAuthController::class, 'verifyEmail'])->name('verification.verify');
    }
);
