<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\JwtAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StickerController;
use App\Http\Controllers\TransactionController;
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



// authentication routes
Route::prefix('auth')->group(
    function ($router) {
        Route::post('/login', [JwtAuthController::class, 'login']);
        Route::post('/register', [JwtAuthController::class, 'register']);
        Route::post('/createUser', [JwtAuthController::class, 'createUser'])->middleware(['jwt_full', 'admin']);

        Route::get('/roles', [JwtAuthController::class, 'getUserRoles']);

        Route::post('/logout', [JwtAuthController::class, 'logout'])->middleware('jwt_full');
        Route::get('/user-profile', [JwtAuthController::class, 'getCurrentUser'])->middleware('jwt_full');
        Route::post('/refresh', [JwtAuthController::class, 'refresh'])->middleware('jwt_basic');

        Route::post('/send-verify-email', [JwtAuthController::class, 'sendEmailVerification']);
        Route::post('/resend-verification', [JwtAuthController::class, 'createNewVerificationLink']);
        // set the where cause otherwise there kept being a problem with the url not being well seen by laravel.
        // now laravel properly sees the verification_token and the redirect_url and makes it one good route.
        Route::get('/verify-email/{verification_token}/{redirect_url}', [JwtAuthController::class, 'verifyEmail'])->name('verification.verify.api')->where('redirect_url', '.*');
    }
);


// sticker routes and images
Route::prefix('stickers')->group(
    function ($router) {
        // Route to get a list of all stickers
        Route::get('/', [StickerController::class, 'index']);
        // Route to get a list of all stickers by categoryId
        Route::get('/category/{categoryId}', [StickerController::class, 'getStickersByCategory']);
        // Route to attach categories to a sticker
        Route::post('/{id}/categories/attach', [StickerController::class, 'attachCategoryToSticker']);
        // Route to detach categories from a sticker
        Route::delete('/{id}/categories/detach', [StickerController::class, 'detachCategoryToSticker']);
        // Route to get a specific sticker by ID
        Route::get('/{id}', [StickerController::class, 'show'])->where('id', '[0-9]+');
        // Route to create a new sticker
        Route::post('/', [StickerController::class, 'store'])->middleware(['jwt_full', 'admin']);
        // Route to update an existing sticker by ID
        Route::put('/{id}', [StickerController::class, 'update'])->middleware(['jwt_full', 'admin']);
        // Route to delete an existing sticker by ID
        Route::delete('/{id}', [StickerController::class, 'destroy'])->middleware(['jwt_full', 'admin']);

        // Route to get the image associated to a sticker
        Route::get('/{stickerId}/images', [ImageController::class, 'show']);
        // Route to create a new image for a specific sticker
        Route::post('/{stickerId}/images', [ImageController::class, 'store'])->middleware(['jwt_full', 'admin']);
        // Route to update an existing image for a specific sticker by ID
        Route::put('/{stickerId}/images/{imageId}', [ImageController::class, 'update'])->middleware(['jwt_full', 'admin']);
        // Route to delete an existing image for a specific sticker by ID
        Route::delete('/{stickerId}/images/{imageId}', [ImageController::class, 'destroy'])->middleware(['jwt_full', 'admin']);


        // get all the details of a list of sticker
        Route::get('/details', [StickerController::class, 'indexDetails']);
        // get all the details of a sticker
        Route::get('/details/{id}', [StickerController::class, 'showDetails'])->where('id', '[0-9]+');
    }
);

Route::prefix('categories')->group(
    function ($router) {
        // Route to get a list of all categories
        Route::get('/', [CategoryController::class, 'index']);
        // Route to get a specific category by ID
        Route::get('/{id}', [CategoryController::class, 'show']);
        // Route to create a new category
        Route::post('/', [CategoryController::class, 'store'])->middleware(['jwt_full', 'admin']);
        // Route to update an existing category by ID
        Route::put('/{id}', [CategoryController::class, 'update'])->middleware(['jwt_full', 'admin']);
        // Route to delete an existing category by ID
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware(['jwt_full', 'admin']);
    }
);


Route::prefix('roles')->group(
    function ($router) {
        // Route to get a list of all roles
        Route::get('/', [RoleController::class, 'index'])->middleware(['jwt_full', 'admin']);
        // Route to get a specific role by ID
        Route::get('/{id}', [RoleController::class, 'show'])->middleware(['jwt_full', 'admin']);
        // Route to create a new role
        Route::post('/', [RoleController::class, 'store'])->middleware(['jwt_full', 'admin']);
        // Route to update an existing role by ID
        Route::put('/{id}', [RoleController::class, 'update'])->middleware(['jwt_full', 'admin']);
        // Route to delete an existing role by ID
        Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware(['jwt_full', 'admin']);
        // Route attach a role to a user.
        Route::post('/{roleId}/user/attach/{userId}', [RoleController::class, 'attachRoleToUser'])->middleware(['jwt_full', 'admin']);
        // Route detach a role from a user.
        Route::delete('/{roleId}/user/detach/{userId}', [RoleController::class, 'detachRoleOfUser'])->middleware(['jwt_full', 'admin']);
    }
);

Route::prefix('transactions')->group(
    function ($router) {
        // Retrieve a list of transactions
        Route::get('', [TransactionController::class, 'index'])->middleware(['jwt_full', 'admin']);
        // Retrieve a specific transaction by ID
        Route::get('/{id}', [TransactionController::class, 'show'])->middleware(['jwt_full'])->where('id', '[0-9]+');
        // Retrieve the transactions of a specific user
        Route::get('/user', [TransactionController::class, 'getUserTransactions'])->middleware(['jwt_full']);
    }
);

Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback')->middleware(['jwt_full']);
Route::post('/payment/sticker', [PaymentController::class, 'initiatePayment'])->middleware(['jwt_full']);
