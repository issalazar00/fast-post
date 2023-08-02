<?php

use App\Http\Controllers\ImportProductController;
use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $dato = Configuration::firstOrFail();
    $today = Carbon::now();
    $expiration = new Carbon($dato->expiration_date);
    return view('layouts.app', ['expiration' => $expiration, 'today' => $today]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/import/download-example-import', [ImportProductController::class, 'downloadExample']);
