<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CutiPerizinanController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\GrupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\RiwayatJabatanController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('home');
});

Route::get('lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, \App\Http\Middleware\SetLocale::SUPPORTED, true), 400);
    session(['locale' => $locale]);

    return back();
})->name('lang.switch');

Auth::routes();

Route::get('password/reset/{token}', function ($token) {
    // This route can be used to display the password reset form.
    // You might want to show a view where users can input their new password.
    return view('auth.passwords.reset', ['token' => $token]);
})->name('password.reset');

Route::post('password/reset', [App\Http\Controllers\Api\Auth\PasswordController::class, 'reset'])
    ->name('password.update');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Leave review pages are reachable by any authenticated user; the
    // CutiPerizinanPolicy scopes them to admins and supervisors (atasan).
    // The numeric constraint keeps `{cutiPerizinan}` from swallowing the
    // admin-only `cuti-perizinan/create` route registered below.
    Route::get('cuti-perizinan/hasil-permohonan', [CutiPerizinanController::class, 'hasilPermohonan'])->name('cuti-perizinan.hasil');
    Route::get('cuti-perizinan', [CutiPerizinanController::class, 'index'])->name('cuti-perizinan.index');
    Route::post('cuti-perizinan/{cutiPerizinan}/approve', [CutiPerizinanController::class, 'approve'])->whereNumber('cutiPerizinan')->name('cuti-perizinan.approve');
    Route::post('cuti-perizinan/{cutiPerizinan}/reject', [CutiPerizinanController::class, 'reject'])->whereNumber('cutiPerizinan')->name('cuti-perizinan.reject');
    Route::post('cuti-perizinan/{cutiPerizinan}/undo', [CutiPerizinanController::class, 'undoApproval'])->whereNumber('cutiPerizinan')->name('cuti-perizinan.undo');
    Route::get('cuti-perizinan/{cutiPerizinan}', [CutiPerizinanController::class, 'show'])->whereNumber('cutiPerizinan')->name('cuti-perizinan.show');
});

// Everything below is admin-only: non-admins only have the dashboard on the
// web app; employee self-service lives on the Sanctum API.
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Archive related routes
    Route::patch('user/{id}/archive', [UserController::class, 'archive'])->name('user.archive');
    Route::get('user/archived', [UserController::class, 'archivedUsers'])->name('user.archived');
    Route::patch('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

    Route::resource('user', UserController::class);

    Route::resource('attendance', AttendanceController::class);

    Route::get('shift/{shift}/assign', [ShiftController::class, 'assignForm'])->name('shift.assignForm');
    Route::post('shift/{shift}/assign', [ShiftController::class, 'assign'])->name('shift.assign');
    Route::resource('shift', ShiftController::class);

    // Jabatan Routes
    Route::resource('jabatan', JabatanController::class);

    // Grup Routes
    Route::resource('grup', GrupController::class);

    // Departemen Routes. Laravel's English inflector "singularizes"
    // departemen to {departeman}, which breaks the implicit binding of
    // Departemen $departemen — pin the parameter name instead.
    Route::resource('departemen', DepartemenController::class)->parameters(['departemen' => 'departemen']);

    // Kantor Routes
    Route::resource('kantor', KantorController::class);

    Route::prefix('riwayat_jabatan')->name('riwayat_jabatan.')->group(function () {
        Route::get('/create/{user_id}', [RiwayatJabatanController::class, 'create'])->name('create'); // Show create form
        Route::post('/store/{user_id}', [RiwayatJabatanController::class, 'store'])->name('store'); // Store new record
        Route::get('/edit/{user_id}/{id}', [RiwayatJabatanController::class, 'edit'])->name('edit'); // Show edit form
        Route::put('/update/{user_id}/{id}', [RiwayatJabatanController::class, 'update'])->name('update'); // Update record
        Route::delete('/destroy/{user_id}/{id}', [RiwayatJabatanController::class, 'destroy'])->name('destroy'); // Delete record
    });

    // Pengumuman Routes
    Route::resource('pengumuman', PengumumanController::class);

    // Cuti — creation and mutation stay admin-only; review/decision routes
    // live in the auth group above, guarded by CutiPerizinanPolicy.
    Route::resource('cuti-perizinan', CutiPerizinanController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::resource('kalender', KalenderController::class);

    Route::put('/payroll/{id}/mark-as-paid', [PayrollController::class, 'markAsPaid'])->name('payroll.markAsPaid');
    Route::get('/payroll/{id}/review', [PayrollController::class, 'Review'])->name('payroll.review');
    Route::put('/payroll/{id}/review', [PayrollController::class, 'markAsReviewed'])->name('payroll.review.submit');
    Route::get('/payroll/{id}/slip', [PayrollController::class, 'downloadSlip'])->name('payroll.slip');
    Route::get('/payroll/calculate', [PayrollController::class, 'calculatePayroll'])->name('payroll.calculate');
    Route::resource('payroll', PayrollController::class);

    // Activity log (audit trail)
    Route::get('activity-log', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('activity-log.index');

    // Failed queue jobs (DLQ)
    Route::get('failed-jobs', [App\Http\Controllers\FailedJobController::class, 'index'])->name('failed-jobs.index');
    Route::post('failed-jobs/retry-all', [App\Http\Controllers\FailedJobController::class, 'retryAll'])->name('failed-jobs.retry-all');
    Route::post('failed-jobs/{uuid}/retry', [App\Http\Controllers\FailedJobController::class, 'retry'])->name('failed-jobs.retry');
    Route::delete('failed-jobs/{uuid}', [App\Http\Controllers\FailedJobController::class, 'destroy'])->name('failed-jobs.destroy');
    Route::delete('failed-jobs', [App\Http\Controllers\FailedJobController::class, 'flush'])->name('failed-jobs.flush');

    // Tunjangan Routes
    Route::post('/tunjangan/store/{id_payroll}', [TunjanganController::class, 'store'])->name('tunjangan.store');
    Route::put('/tunjangan/{id}', [TunjanganController::class, 'update'])->name('tunjangan.update');
    Route::delete('/tunjangan/{id}', [TunjanganController::class, 'destroy'])->name('tunjangan.destroy');

    // Potongan Routes
    Route::post('/potongan/store/{id_payroll}', [PotonganController::class, 'store'])->name('potongan.store');
    Route::put('/potongan/{id}', [PotonganController::class, 'update'])->name('potongan.update');
    Route::delete('/potongan/{id}', [PotonganController::class, 'destroy'])->name('potongan.destroy');
});
