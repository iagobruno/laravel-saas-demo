<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PricingPageController;
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

Route::get('/precos', [PricingPageController::class, 'show'])->name('prices');
Route::post('/checkout', [PricingPageController::class, 'redirectToCheckout'])->middleware('auth')->name('checkout');
Route::get('/billing', function () {
    return request()->user()->redirectToBillingPortal(route('landing'));
})->middleware(['auth', 'subscribers'])->name('billing');

/**
 * Auth routes
 */
Route::get('/login', fn () => redirect('landing'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
