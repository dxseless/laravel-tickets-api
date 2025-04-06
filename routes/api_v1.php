<?php

use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserTicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class)->except(['update']);
    Route::apiResource('users', UserController::class);
    Route::apiResource('users.tickets', UserTicketsController::class)->except(['update']);
    Route::put('tickets/{ticket}', [TicketController::class, 'replace']);
    Route::put('users/{user}/tickets/{ticket}', [UserTicketsController::class, 'replace']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
