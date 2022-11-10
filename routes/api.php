<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
 
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/createQr', [UserController::class, 'createQr']);
Route::post('/getDataQr', [UserController::class, 'getDataQr']);
Route::post('/getDataUsers', [UserController::class, 'getDataUsers']);
Route::post('/getDataQrOne', [UserController::class, 'getDataQrOne']);