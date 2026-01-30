<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('v1')
    ->group(function () {
        Route::get('/health', function () {
            return response()->json([
                'status' => 'ok',
                'timestamp' => now()->toIso8601String(),
            ]);
        });
    });
