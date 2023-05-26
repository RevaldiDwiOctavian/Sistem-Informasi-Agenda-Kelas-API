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

Route::post('/register', [App\Http\Controllers\API\Auth\RegisterController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\Auth\AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/profile',  [App\Http\Controllers\API\Auth\AuthController::class, 'profile']);

    //Admin
    Route::get('/admin/users/admin', [App\Http\Controllers\API\Admin\UserManagementController::class, 'getUserAdmin']);
    Route::get('/admin/users/guru', [App\Http\Controllers\API\Admin\UserManagementController::class, 'getUserGuru']);
    Route::get('/admin/users/siswa', [App\Http\Controllers\API\Admin\UserManagementController::class, 'getUserSiswa']);

    Route::post('/logout', [App\Http\Controllers\API\Auth\AuthController::class, 'logout']);
});
