<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| AUTH USER (REACT)
|--------------------------------------------------------------------------
*/

Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login',    [UserAuthController::class, 'login']);

Route::get('/login/google',          [UserAuthController::class, 'redirectToGoogle']);
Route::get('/login/google/callback', [UserAuthController::class, 'handleGoogleCallback']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [UserAuthController::class, 'logout']);

    /*
     * ✅ FIX: pakai method me() di controller
     *    supaya return { user: {...} } dan selalu fresh dari DB
     *    (bukan langsung return $request->user() yang bisa stale)
     */
    Route::get('/me',  [UserAuthController::class, 'me']);
    Route::post('/me', [UserAuthController::class, 'updateProfile']);
});

/*
|--------------------------------------------------------------------------
| PUBLIC DATA (EVENT, ARTIST, TICKET)
|--------------------------------------------------------------------------
*/

Route::apiResource('events',  EventController::class)->only(['index', 'show']);
Route::apiResource('artists', ArtistController::class)->only(['index', 'show']);
Route::apiResource('tickets', TicketController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| TRANSACTIONS (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/transactions',                        [TransactionController::class, 'index']);
    Route::post('/transactions',                       [TransactionController::class, 'store']);
    Route::post('/transactions/{transaction}/pay',     [TransactionController::class, 'pay']);
});