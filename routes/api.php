<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::post('/login', [App\Http\Controllers\UserController::class,'login']);
Route::post('/register', [App\Http\Controllers\UserController::class,'register']);


// Route::middleware('auth:api')->group(function(){
    Route::resource('/user', App\Http\Controllers\UserController::class);

    
    Route::resource('/category', App\Http\Controllers\CategoryController::class);
    Route::post('/category/{category}/activate',  [CategoryController::class, 'activate']);
    Route::post('/category/{category}/deactivate',  [CategoryController::class, 'deactivate']);

    Route::resource('/tax', App\Http\Controllers\TaxController::class);
    Route::post('/tax/{tax}/activate',  [TaxController::class, 'activate']);
    Route::post('/tax/{tax}/deactivate',  [TaxController::class, 'deactivate']);

    Route::resource('/products',  ProductController::class);
    Route::post('/products/{product}/activate',  [ProductController::class, 'activate']);
    Route::post('/products/{product}/deactivate',  [ProductController::class, 'deactivate']);
// });