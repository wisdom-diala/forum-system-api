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

Route::middleware('auth:sanctum')->group(function () {
    // return $request->user();
    Route::post('logout', [App\Http\Controllers\User\UserAuthController::class, 'logout']);

});

Route::post('register', [App\Http\Controllers\User\UserAuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\User\UserAuthController::class, 'login']);

require __DIR__ . '/admin.php';
