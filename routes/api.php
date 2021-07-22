<?php

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


Route::middleware('auth:api')->group(function(){
    Route::resource('/user', App\Http\Controllers\UserController::class);
    Route::resource('/category', App\Http\Controllers\CategoryController::class);
    Route::resource('/tax', App\Http\Controllers\TaxController::class);
});