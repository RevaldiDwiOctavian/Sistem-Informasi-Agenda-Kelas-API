<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserManagement\UserManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserSiswaManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserAdminManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserGuruManagementController;
use App\Http\Controllers\API\Admin\GuruManagement\GuruManagementController;

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

// Aut Route
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/user',  [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('admin')->group(function () {
        // Admin Route
        // User Management Route
        Route::post('/users', [UserManagementController::class, 'addUser']);
        Route::patch('users/{id}', [UserManagementController::class, 'updateUsers']);
        Route::delete('users/{id}', [UserManagementController::class, 'deleteUser']);

        // User Admin
        Route::get('/users/admin', [UserAdminManagementController::class, 'getUserAdmin']);
        Route::get('/users/admin/{id}', [UserAdminManagementController::class, 'getUserAdminById']);

        // User Guru
        Route::get('/users/guru', [UserGuruManagementController::class, 'getUserGuru']);
        Route::get('/users/guru/{id}', [UserGuruManagementController::class, 'getUserGuruById']);

        // User Siswa
        Route::get('/users/siswa', [UserSiswaManagementController::class, 'getUserSiswa']);
        Route::get('/users/siswa/{id}', [UserSiswaManagementController::class, 'getUserSiswaById']);

        // Guru Management Route
        Route::post('/guru', [GuruManagementController::class, 'addGuru']);
        Route::get('/gurus', [GuruManagementController::class, 'showGurus']);
        Route::put('/guru/{id}', [GuruManagementController::class, 'updateGuru']);
        Route::delete('/guru/{id}', [GuruManagementController::class, 'deleteGuru']);

    });

});
