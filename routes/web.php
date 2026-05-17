<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReactAccessController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH (GUEST ACCESS)
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');

Route::get('/admin/login/google', [AdminAuthController::class, 'redirectToGoogle'])
    ->name('admin.login.google');

Route::get('/admin/login/google/callback', [AdminAuthController::class, 'handleGoogleCallback'])
    ->name('admin.login.google.callback');

/*
|--------------------------------------------------------------------------
| ADMIN PROTECTED AREA
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {

        /*
        | DASHBOARD
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/react', [ReactAccessController::class, 'open'])
            ->name('react.open');

        /*
        | PROFILE
        */
        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::post('/profile/update', [ProfileController::class, 'update'])
            ->name('profile.update');

        /*
        | CRUD
        */
        Route::resource('events', EventController::class);
        Route::resource('artists', ArtistController::class);
        Route::resource('tickets', TicketController::class)->except('show');
        Route::resource('users', UserController::class)->except('show');

        /*
        | TRANSACTIONS
        */
        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        /*
        | LOGOUT (FIX DI SINI)
        */
        Route::post('/logout', [AdminAuthController::class, 'logout'])
            ->name('logout');
    });
