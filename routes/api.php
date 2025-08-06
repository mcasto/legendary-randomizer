<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EntitiesController;
use App\Http\Controllers\BuildDeckController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

Route::get(
    '/build-deck/{numPlayers}',
    [
        BuildDeckController::class,
        'show'
    ]
)
    ->middleware('auth:sanctum')
    ->name('build-deck');

Route::get('/entities', [EntitiesController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('entities');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')
    ->name('logout');

Route::post('/verify-token', function (Request $request): JsonResponse {
    return response()->json(['status' => 'success']);
})
    ->middleware('auth:sanctum')
    ->name('verify-token');
