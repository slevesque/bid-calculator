<?php

use App\Http\Controllers\Api\BidCalculatorController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/bid-calculator/calculate', [BidCalculatorController::class, 'calculate'])
        ->name('bid-calculator.calculate');
});
