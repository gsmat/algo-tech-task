<?php

use App\Http\Controllers\BondController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1/bond')->group(function () {
    Route::get('/{id}/payouts', [BondController::class, 'interestDate'])->name('calculate.interest.date');
    Route::post('/{id}/order', [OrderController::class, 'orderBond'])->name('order.bond');
    Route::post('/order/{orderId}', [OrderController::class, 'interestPayments'])->name('interest.payments');
});
