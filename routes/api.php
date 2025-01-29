<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\CutiPerizinanController;

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
    Route::apiResource('pengumuman', PengumumanController::class);
});

Route::put('cuti-perizinan/{id}', [CutiPerizinanController::class, 'update']);
Route::post('cuti-perizinan', [CutiPerizinanController::class, 'store']);
Route::get('cuti-perizinan', [CutiPerizinanController::class, 'getAllPermohonan']);
Route::get('cuti-perizinan/{id}', [CutiPerizinanController::class, 'getPermohonanById']);

