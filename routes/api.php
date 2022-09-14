<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::resource('post', PostController::class);

Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register')->name('register');
    Route::post('logout', 'logout');
    Route::get('redirect-unauthorized', 'redirectUnauthorized')->name('redirectUnauthorized');

    Route::post('password/reset-send-email', 'sendEmailReset')->name('sendEmailReset');
    Route::post('password/confirmed-token', 'confirmedToken')->name('confirmedToken');
    Route::post('password/update', 'updatePassword')->name('updatePassword');

});
