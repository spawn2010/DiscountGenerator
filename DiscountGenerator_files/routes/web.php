<?php

use Illuminate\Support\Facades\Auth;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/discount', [App\Http\Controllers\DiscountController::class, 'index']);

Route::post('/discount/create', [App\Http\Controllers\DiscountController::class, 'createDiscount']);

Route::post('/discount/check', [App\Http\Controllers\DiscountController::class, 'checkDiscount']);


