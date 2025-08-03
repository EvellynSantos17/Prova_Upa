<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;


Route::get('/', function () {
    return response()->json(['message' => 'API Laravel']);
});
Route::get('/teste', function () {
    return response()->json(['message' => 'TESTE laravel']);
});

Route::post('/create/itens', [ItemController::class, 'store']);