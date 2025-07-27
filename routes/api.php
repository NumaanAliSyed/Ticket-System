<?php

use App\Http\Controllers\TicketApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
});