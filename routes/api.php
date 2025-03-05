<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\CutiPerizinanController;
use App\Http\Controllers\Api\KalenderController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\PayrollController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
   
    Route::post('/password/reset', [PasswordController::class, 'reset'])
        ->middleware('auth:sanctum');
    
    Route::post('/password/forgot', [PasswordController::class, 'sendResetLinkEmail']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('attendance', [AttendanceController::class, 'store']);
    Route::get('attendance/history', [AttendanceController::class, 'history']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('pengumuman', [PengumumanController::class, 'index'])->name('api.pengumuman.index');
    Route::post('pengumuman', [PengumumanController::class, 'store'])->name('api.pengumuman.store');
    Route::get('pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('api.pengumuman.show');
    Route::put('pengumuman/{pengumuman}', [PengumumanController::class, 'update'])->name('api.pengumuman.update');
    Route::delete('pengumuman/{pengumuman}', [PengumumanController::class, 'destroy'])->name('api.pengumuman.destroy');
});

Route::put('cuti-perizinan/{id}', [CutiPerizinanController::class, 'update']);
Route::post('cuti-perizinan', [CutiPerizinanController::class, 'store']);
Route::get('cuti-perizinan', [CutiPerizinanController::class, 'getAllPermohonan']);
Route::get('cuti-perizinan/{id}', [CutiPerizinanController::class, 'getPermohonanById']);

Route::get('/kalender', [KalenderController::class, 'index']);

Route::get('/shift/{shiftId}/users', [ShiftController::class, 'getUsersByShift']);
Route::get('/users/{userId}/shifts', [UserController::class, 'getShiftsByUser']);

Route::get('/users/{userId}/payroll', [PayrollController::class, 'getPayrollByUserId']);
Route::get('/payroll/{payrollId}', [PayrollController::class, 'getPayrollById']);