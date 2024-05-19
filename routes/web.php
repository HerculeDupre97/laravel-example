<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',  [SiteController::class, 'viewHome']);
Route::get('/latest-rates', [SiteController::class, 'getLatestRates']);
Route::post('/convert-currency', [SiteController::class, 'convertCurrency']);
