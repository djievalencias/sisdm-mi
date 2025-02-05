<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Passwords\PasswordBroker;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\CutiPerizinanController;
use App\Http\Controllers\KalenderController;

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

// Archive related routes
Route::patch('user/{id}/archive', [UserController::class, 'archive'])->name('user.archive');
Route::get('user/archived', [UserController::class, 'archivedUsers'])->name('user.archived');
Route::patch('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

Route::resource('user', App\Http\Controllers\UserController::class);

Route::resource('attendance', App\Http\Controllers\AttendanceController::class)->only(['index', 'show']);

Route::get('shift/{shift}/assign', [ShiftController::class, 'assignForm'])->name('shift.assignForm');
Route::post('shift/{shift}/assign', [ShiftController::class, 'assign'])->name('shift.assign');

Route::resource('shift', App\Http\Controllers\ShiftController::class);

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

// Riwayat Jabatan Routes
// Explicit route for creating a new Riwayat Jabatan with a specific user
Route::get('/riwayat-jabatan/create-for-user/{user}', [RiwayatJabatanController::class, 'create'])
    ->name('riwayat-jabatan.create-for-user')
    ->middleware(['auth', 'is_admin']);
Route::post('/riwayat-jabatan/create-for-user/{user}', [RiwayatJabatanController::class, 'store'])
    ->name('riwayat-jabatan.create-for-user')
    ->middleware(['auth', 'is_admin']);

// Resource routes for Riwayat Jabatan
Route::resource('riwayat-jabatan', RiwayatJabatanController::class)
    ->except(['create', 'show', 'store']) // Exclude the conflicting 'create' route
    ->middleware(['auth', 'is_admin']);
// Pengumuman Routes
Route::resource('pengumuman', PengumumanController::class);

// Cuti
Route::get('cuti-perizinan/hasil-permohonan', [CutiPerizinanController::class, 'hasilPermohonan'])->name('cuti-perizinan.hasil');
Route::resource('cuti-perizinan', CutiPerizinanController::class);
Route::post('/cuti-perizinan/{cutiPerizinan}/approve', [CutiPerizinanController::class, 'approve'])->name('cuti-perizinan.approve');
Route::post('/cuti-perizinan/{cutiPerizinan}/reject', [CutiPerizinanController::class, 'reject'])->name('cuti-perizinan.reject');
Route::post('/cuti-perizinan/{cutiPerizinan}/undo', [CutiPerizinanController::class, 'undoApproval'])->name('cuti-perizinan.undo');

Route::resource('kalender', KalenderController::class);