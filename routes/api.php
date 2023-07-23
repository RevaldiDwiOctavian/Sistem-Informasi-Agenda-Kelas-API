<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Admin\UserManagement\UserManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserSiswaManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserAdminManagementController;
use App\Http\Controllers\API\Admin\UserManagement\UserGuruManagementController;
use App\Http\Controllers\API\Admin\GuruManagement\GuruManagementController;
use App\Http\Controllers\API\Admin\SiswaManagement\SiswaManagementController;
use App\Http\Controllers\API\Admin\RombelManagement\RombelManagementController;
use App\Http\Controllers\API\Admin\PembelajaranManagement\PembelajaranManagementController;
use App\Http\Controllers\API\Admin\AgendaKelasManagement\AgendaKelasManagementController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::prefix('admin')->group(function () {
        // Admin Route
        // User Management Route
        Route::post('/users', [UserManagementController::class, 'addUser']);
        Route::patch('users/{id}', [UserManagementController::class, 'updateUsers']);
        Route::delete('users/{id}', [UserManagementController::class, 'deleteUser']);
        Route::delete('users/deactivate/{id}', [UserManagementController::class, 'deactivateUser']);
        Route::delete('users/activate/{id}', [UserManagementController::class, 'activateUser']);

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
        Route::get('/guru/{id}', [GuruManagementController::class, 'getGuruById']);
        Route::get('/guru-total', [GuruManagementController::class, 'getTotalGuru']);
        Route::put('/guru/{id}', [GuruManagementController::class, 'updateGuru']);
        Route::delete('/guru/{id}', [GuruManagementController::class, 'deleteGuru']);

        // Siswa Management Route
        Route::post('/siswa', [SiswaManagementController::class, 'addSiswa']);
        Route::get('/siswa', [SiswaManagementController::class, 'showSiswas']);
        Route::get('/siswa/{id}', [SiswaManagementController::class, 'getSiswaById']);
        Route::get('/siswa-total', [SiswaManagementController::class, 'getTotalSiswa']);
        Route::put('/siswa/{id}', [SiswaManagementController::class, 'updateSiswa']);
        Route::delete('/siswa/{id}', [SiswaManagementController::class, 'deleteSiswa']);

        // Rombel Management Route
        Route::post('/rombel', [RombelManagementController::class, 'addRombel']);
        Route::get('/rombels', [RombelManagementController::class, 'showRombels']);
        Route::get('/rombel/{id}', [RombelManagementController::class, 'getRombelById']);
        Route::put('/rombel/{id}', [RombelManagementController::class, 'updateRombel']);
        Route::delete('/rombel/{id}', [RombelManagementController::class, 'deleteRombel']);

        // Pembelajaran Management Route
        Route::post('/pembelajaran', [PembelajaranManagementController::class, 'addPembelajaran']);
        Route::get('/pembelajarans', [PembelajaranManagementController::class, 'showPembelajarans']);
        Route::get('/pembelajaran/{id}', [PembelajaranManagementController::class, 'getPembelajaranById']);
        Route::put('/pembelajaran/{id}', [PembelajaranManagementController::class, 'updatePembelajaran']);
        Route::delete('/pembelajaran/{id}', [PembelajaranManagementController::class, 'deletePembelajaran']);

        // Agenda Kelas Management Route
        Route::post('/agenda-kelas', [AgendaKelasManagementController::class, 'addAgendaKelas']);
        Route::get('/agenda-kelas', [AgendaKelasManagementController::class, 'showAgendaKelas']);

    });

});
