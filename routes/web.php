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
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\PotonganController;

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

Route::resource('attendance', AttendanceController::class);

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

Route::prefix('riwayat_jabatan')->name('riwayat_jabatan.')->group(function () {
    Route::get('/create/{user_id}', [RiwayatJabatanController::class, 'create'])->name('create'); // Show create form
    Route::post('/store/{user_id}', [RiwayatJabatanController::class, 'store'])->name('store'); // Store new record
    Route::get('/edit/{user_id}/{id}', [RiwayatJabatanController::class, 'edit'])->name('edit'); // Show edit form
    Route::put('/update/{user_id}/{id}', [RiwayatJabatanController::class, 'update'])->name('update'); // Update record
    Route::delete('/destroy/{user_id}/{id}', [RiwayatJabatanController::class, 'destroy'])->name('destroy'); // Delete record
});

// Pengumuman Routes
Route::resource('pengumuman', PengumumanController::class);

// Cuti
Route::get('cuti-perizinan/hasil-permohonan', [CutiPerizinanController::class, 'hasilPermohonan'])->name('cuti-perizinan.hasil');
Route::resource('cuti-perizinan', CutiPerizinanController::class);
Route::post('/cuti-perizinan/{cutiPerizinan}/approve', [CutiPerizinanController::class, 'approve'])->name('cuti-perizinan.approve');
Route::post('/cuti-perizinan/{cutiPerizinan}/reject', [CutiPerizinanController::class, 'reject'])->name('cuti-perizinan.reject');
Route::post('/cuti-perizinan/{cutiPerizinan}/undo', [CutiPerizinanController::class, 'undoApproval'])->name('cuti-perizinan.undo');

Route::resource('kalender', KalenderController::class);
Route::get('/payroll/calculate', [PayrollController::class, 'calculatePayroll'])->name('payroll.calculate');
Route::resource('payroll', PayrollController::class);

// Tunjangan Routes
Route::get('/tunjangan/create/{id_payroll}', [TunjanganController::class, 'create'])->name('tunjangan.create');
Route::post('/tunjangan/store/{id_payroll}', [TunjanganController::class, 'store'])->name('tunjangan.store');
Route::get('/tunjangan/{id}/edit', [TunjanganController::class, 'edit'])->name('tunjangan.edit');
Route::put('/tunjangan/{id}', [TunjanganController::class, 'update'])->name('tunjangan.update');
Route::delete('/tunjangan/{id}', [TunjanganController::class, 'destroy'])->name('tunjangan.destroy');

// Potongan Routes
Route::get('/potongan/create/{id_payroll}', [PotonganController::class, 'create'])->name('potongan.create');
Route::post('/potongan/store/{id_payroll}', [PotonganController::class, 'store'])->name('potongan.store');
Route::get('/potongan/{id}/edit', [PotonganController::class, 'edit'])->name('potongan.edit');
Route::put('/potongan/{id}', [PotonganController::class, 'update'])->name('potongan.update');
Route::delete('/potongan/{id}', [PotonganController::class, 'destroy'])->name('potongan.destroy');
