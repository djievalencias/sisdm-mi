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

// 🔹 Auth Routes (Public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [PasswordController::class, 'sendResetLinkEmail']);
});

// 🔹 Protected Routes (Require Authentication)
Route::middleware('auth:sanctum')->group(function () {
    
    // 🔹 Authenticated User Info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 🔹 Authenticated Actions
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/password/reset', [PasswordController::class, 'reset']);

    // 🔹 User Routes
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/{userId}/shifts', [UserController::class, 'getShiftsByUser']);
        Route::get('/{userId}/payroll', [PayrollController::class, 'getPayrollByUserId']);
    });

    // 🔹 Attendance Routes
    Route::prefix('attendance')->group(function () {
        Route::post('/', [AttendanceController::class, 'store']);
        Route::get('/history', [AttendanceController::class, 'history']);
    });

    // 🔹 Payroll Routes
    Route::prefix('payroll')->group(function () {
        Route::get('/{payrollId}', [PayrollController::class, 'getPayrollById']);
    });

    // 🔹 Announcement Routes
    Route::apiResource('pengumuman', PengumumanController::class, array("as" => "api"));

    // 🔹 Shift Routes
    Route::get('/shift/{shiftId}/users', [ShiftController::class, 'getUsersByShift']);

    // 🔹 Leave & Permission Routes (Cuti Perizinan)
    Route::prefix('cuti-perizinan')->group(function () {
        Route::get('/', [CutiPerizinanController::class, 'getAllPermohonan']);
        Route::get('/{id}', [CutiPerizinanController::class, 'getPermohonanById']);
        Route::post('/', [CutiPerizinanController::class, 'store']);
        Route::put('/{id}', [CutiPerizinanController::class, 'update']);
    });

    // 🔹 Calendar Routes
    Route::get('/kalender', [KalenderController::class, 'index']);
});
