<?php

use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ExchangeOfferController;
use App\Http\Controllers\Api\V1\ExchangeRequestController;
use App\Http\Controllers\Api\V1\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::apiResource('categories', CategoryController::class)->only('index');
    Route::apiResource('tags', TagController::class)->only('index');
    Route::apiResource('books', BookController::class)->only(['index', 'show']);
    Route::get('exchange-requests/{exchange_request}/offers', [ExchangeOfferController::class, 'index']);
    Route::post('exchange-requests/{exchange_request}/offers', [ExchangeOfferController::class, 'store']);
    Route::apiResource('exchange-requests', ExchangeRequestController::class);
    Route::apiResource('exchange-offers', ExchangeOfferController::class)->only(['show', 'update', 'destroy']);
});
