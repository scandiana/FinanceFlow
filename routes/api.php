<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API FinanceFlow funcionando!'
    ]);
});

Route::apiResource('clients', ClientController::class);