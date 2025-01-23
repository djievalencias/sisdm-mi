<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KantorController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('user', App\Http\Controllers\UserController::class);
Route::resource('attendance', App\Http\Controllers\AttendanceController::class)->only(['index', 'show']);

Route::get('password/reset/{token}', function ($token) {
    // This route can be used to display the password reset form.
    // You might want to show a view where users can input their new password.
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('password/reset', [App\Http\Controllers\Api\Auth\PasswordController::class, 'reset'])
    ->name('password.update');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Jabatan Routes
Route::resource('jabatan', JabatanController::class)->middleware(['auth', 'is_admin']);

// Grup Routes
Route::resource('grup', GrupController::class)->middleware(['auth', 'is_admin']);

// Departemen Routes
Route::resource('departemen', DepartemenController::class)->middleware(['auth', 'is_admin']);

// Kantor Routes
Route::resource('kantor', KantorController::class)->middleware(['auth', 'is_admin']);
Route::get('/kantor/{kantor}/edit', [KantorController::class, 'edit'])->name('kantor.edit');