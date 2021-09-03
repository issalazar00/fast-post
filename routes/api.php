<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ImportProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('/users', UserController::class);
    Route::post('/users/{user}/activate',  [UserController::class, 'activate']);
    Route::post('/users/{user}/deactivate',  [UserController::class, 'deactivate']);
    Route::post('/register', [UserController::class, 'register']);
    
    Route::resource('/categories', CategoryController::class);
    Route::post('/categories/{category}/activate',  [CategoryController::class, 'activate']);
    Route::post('/categories/{category}/deactivate',  [CategoryController::class, 'deactivate']);

    Route::resource('/taxes', TaxController::class);
    Route::post('/taxes/{tax}/activate',  [TaxController::class, 'activate']);
    Route::post('/taxes/{tax}/deactivate',  [TaxController::class, 'deactivate']);

    Route::resource('/brands', BrandController::class);

    Route::resource('/products',  ProductController::class);
    Route::post('/products/{product}/activate',  [ProductController::class, 'activate']);
    Route::post('/products/{product}/deactivate',  [ProductController::class, 'deactivate']);
    Route::post('/products/searchProduct',  [ProductController::class, 'searchProduct']);


    Route::resource('/suppliers',  SupplierController::class);

    Route::resource('/clients',  ClientController::class);
    Route::post('/clients/searchClient',  [ClientController::class, 'searchClient']);
    Route::post('/clients/filterClientList',  [ClientController::class, 'filterClientList']);



    Route::get('/roles/getAllRoles', [RoleController::class, 'getAllRoles']);
    Route::resource('/roles', RoleController::class);
    Route::get('/permissions', [RoleController::class, 'getPermissions']);
});
