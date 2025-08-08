<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EntitiesController;
use App\Http\Controllers\BuildDeckController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SetController;
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

Route::get('/keywords', [KeywordController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('keywords.index');

Route::get('/user-settings', function () {
    $user = auth()->user();
    return response()->json($user->settings());
})
    ->middleware('auth:sanctum')
    ->name('user.settings');

Route::get('/sets', [SetController::class, 'index'])
    ->middleware('auth:sanctum')
    ->name('sets.index');

Route::put('/sets/{set_value}/add', [SetController::class, 'addSet'])
    ->middleware('auth:sanctum')
    ->name('sets.add');

Route::put('/sets/{set_value}/remove', [SetController::class, 'removeSet'])
    ->middleware('auth:sanctum')
    ->name('sets.remove');
