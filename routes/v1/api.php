<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TransactionController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for version 1 of your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group with the prefix "api/v1".
|
*/

// Authentication Routes
Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');

        // Protected routes
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('me', [AuthController::class, 'me'])->name('me');
            Route::get('refresh', [AuthController::class, 'refresh'])->name('refresh');
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        });
    });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('users/active', [UserController::class, 'active'])->name('users.active');
    Route::get('users/all', [UserController::class, 'all'])->name('users.all');
    Route::apiResource('users', UserController::class)->names('users');

    
    // Transactions Route
    Route::get('/transactions/config', [TransactionController::class, 'config'])->name('transactions.get_config');
    Route::post('/transactions/config', fn() => 'Coming Soon')->name('transactions.set_config');

    Route::get('/transactions/metrics', [TransactionController::class, 'metrics'])->name('transactions.metrics');
    Route::apiResource('transactions', TransactionController::class)->names('transactions');

    Route::get('/users/{user_id}/alerts', [TransactionController::class, 'alerts'])->name('transactions.alerts');
});

// Health Check
Route::get('/health', function () {
    return response()->json([
        'message' => 'Vigilant transactions service is running'
    ]);
});
