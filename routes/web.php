<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return view('bid_calculator', [
        'bid_calculator_api_endpoint' => config('services.bid_calculator.endpoint'),
        'bid_calculator_api_key' => config('services.bid_calculator.key'),
    ]);
});
