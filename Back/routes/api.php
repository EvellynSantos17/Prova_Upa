<?php

use App\Http\Controllers\API\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SetorController;



Route::prefix('v1')->group(function () {
    Route::get('/setores', [SetorController::class, 'index']);
    
    // Endpoints que requerem usuário não autenticado:
    Route::middleware(['guest:sanctum'])->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login',    [AuthController::class, 'login']);
    });
    
    // Endpoints que requerem autenticação:
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user',    [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::post('/create/itens', [ItemController::class, 'store']);
});
