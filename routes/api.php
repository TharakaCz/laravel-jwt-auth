<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UsersController::class)->prefix('user')->group(function (){
    Route::get('all', 'getAll');
    Route::get('list', 'getList');
    Route::get('auth/token/refresh', 'refresh');
    Route::post('auth/logout', 'logout');
    Route::post('auth/login', 'login');
    Route::post('auth/register', 'register');
});
