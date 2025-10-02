<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Rutas API públicas
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas API protegidas con Sanctum
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Recursos principales
    Route::apiResource('products', ProductController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('sales', SaleController::class);

    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::get('/sales/{id}/print', [SaleController::class, 'print'])
    ->middleware('auth:sanctum');
