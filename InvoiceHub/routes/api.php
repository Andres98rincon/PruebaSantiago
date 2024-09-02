<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

// Rutas para la gestión de facturas
Route::middleware('api', 'auth')->prefix('invoices')->controller(InvoiceController::class)->group(function () {
    // Ruta para listar todas las facturas
    Route::get('list', 'index');

    // Ruta para crear una nueva factura
    Route::post('store', 'store');

    // Ruta para mostrar una factura específica
    Route::get('{invoice}/show', 'show');

    // Ruta para actualizar una factura específica
    Route::put('{invoice}/update', 'update');
});

// Rutas para la gestión de entidades
Route::middleware('api', 'auth')->prefix('entities')->controller(EntityController::class)->group(function () {
    // Ruta para listar todas las entidades
    Route::get('list', 'index');

    // Ruta para crear una nueva entidad
    Route::post('store', 'store');

    // Ruta para mostrar una entidad específica
    Route::get('{entity}/show', 'show');

    // Ruta para actualizar una entidad específica
    Route::patch('{entity}/update', 'update');

    // Ruta para eliminar una entidad específica
    Route::delete('{entity}/destroy', 'destroy');
});


Route::middleware('api', 'auth')->prefix('products')->controller(ProductController::class)->group(function () {
    // Ruta para listar todos los productos
    Route::get('list', 'index');
});
