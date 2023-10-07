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
        Route::post('/send-verify-email', [JwtAuthController::class, 'sendEmailVerification']);
        Route::post('/resend-verification', [JwtAuthController::class, 'createNewVerificationLink']);
        // set the where cause otherwise there kept being a problem with the url not being well seen by laravel.
        // now laravel properly sees the verification_token and the redirect_url and makes it one good route.
        Route::get('/verify-email/{verification_token}/{redirect_url}', [JwtAuthController::class, 'verifyEmail'])->name('verification.verify.api')->where('redirect_url', '.*');
    }
);


Route::prefix('auth')->middleware(['jwt'])->group(function ($router) {
    Route::post('/logout', [JwtAuthController::class, 'logout']);
    Route::post('/refresh', [JwtAuthController::class, 'refresh']);
    Route::get('/user-profile', [JwtAuthController::class, 'getCurrentUser']);
});
