<?php

use App\Http\Controllers\AuthController;
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

Route::view('/', 'landing')->name('landing');

/**
 * Auth routes
 */
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
