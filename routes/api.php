<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashoutController;
use App\Http\Controllers\ApiPaymentsController;
use App\Http\Controllers\OrderController;

Route::group(['prefix' => 'users'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::get('packages', [OrderController::class, 'allPackages']);

    Route::group(['middleware' => 'auth:sanctum'], function() {
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
      Route::get('cashout', [ApiPaymentsController::class, 'cashoutFunds']);
      Route::get('teams', [AuthController::class, 'userTeams']);
      Route::get('trxs', [ApiPaymentsController::class, 'getUserTransactions']);
      Route::get('order', [OrderController::class, 'placeOrder']);
    });
});
