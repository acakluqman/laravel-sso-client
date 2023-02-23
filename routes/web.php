<?php

use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SSOController;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

Route::middleware(['sso'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('/');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/user', [SSOController::class, 'getUser'])->name('user');
});

Route::get('/login', [SSOController::class, 'getLogin'])->name('login.api');
Route::get('/callback', [SSOController::class, 'getCallback'])->name('callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
