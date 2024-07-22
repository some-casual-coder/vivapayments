<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/create-order', [PaymentController::class, 'createOrder']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
