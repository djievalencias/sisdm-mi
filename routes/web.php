<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    JabatanController,
    RiwayatJabatanController,
    GrupController,
    DepartemenController,
    KantorController,
    ShiftController,
    UserController,
    PengumumanController,
    CutiPerizinanController,
    KalenderController,
    AttendanceController,
    PayrollController,
    TunjanganController,
    PotonganController,
    HomeController
};

// Authentication Routes
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

// Home Route
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Protect all web routes for admin access only
Route::middleware(['auth', 'is_admin'])->group(function () {
    // User Management
    Route::patch('user/{id}/archive', [UserController::class, 'archive'])->name('user.archive');
    Route::get('user/archived', [UserController::class, 'archivedUsers'])->name('user.archived');
    Route::patch('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
    Route::resource('user', UserController::class);

    // Attendance
    Route::resource('attendance', AttendanceController::class);

    // Shift Management
    Route::get('shift/{shift}/assign', [ShiftController::class, 'assignForm'])->name('shift.assignForm');
    Route::post('shift/{shift}/assign', [ShiftController::class, 'assign'])->name('shift.assign');
    Route::resource('shift', ShiftController::class);

    // Jabatan, Grup, Departemen, Kantor
    Route::resource('jabatan', JabatanController::class);
    Route::resource('grup', GrupController::class);
    Route::resource('departemen', DepartemenController::class)->parameters([
        'departemen' => 'departemen'
    ]);
    Route::resource('kantor', KantorController::class);


    Route::get('/kantor/{kantor}/edit', [KantorController::class, 'edit'])->name('kantor.edit');

    // Riwayat Jabatan
    Route::prefix('riwayat_jabatan')->name('riwayat_jabatan.')->group(function () {
        Route::get('/create/{user_id}', [RiwayatJabatanController::class, 'create'])->name('create');
        Route::post('/store/{user_id}', [RiwayatJabatanController::class, 'store'])->name('store');
        Route::get('/edit/{user_id}/{id}', [RiwayatJabatanController::class, 'edit'])->name('edit');
        Route::put('/update/{user_id}/{id}', [RiwayatJabatanController::class, 'update'])->name('update');
        Route::delete('/destroy/{user_id}/{id}', [RiwayatJabatanController::class, 'destroy'])->name('destroy');
    });

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class);

    // Cuti & Perizinan
    Route::get('cuti-perizinan/hasil-permohonan', [CutiPerizinanController::class, 'hasilPermohonan'])->name('cuti-perizinan.hasil');
    Route::post('cuti-perizinan/{cutiPerizinan}/approve', [CutiPerizinanController::class, 'approve'])->name('cuti-perizinan.approve');
    Route::post('cuti-perizinan/{cutiPerizinan}/reject', [CutiPerizinanController::class, 'reject'])->name('cuti-perizinan.reject');
    Route::post('cuti-perizinan/{cutiPerizinan}/undo', [CutiPerizinanController::class, 'undoApproval'])->name('cuti-perizinan.undo');
    Route::resource('cuti-perizinan', CutiPerizinanController::class)->except(['create']);

    // Kalender
    Route::resource('kalender', KalenderController::class);

    // Payroll
    Route::prefix('payroll')->name('payroll.')->group(function () {
        Route::put('{id}/mark-as-paid', [PayrollController::class, 'markAsPaid'])->name('markAsPaid');
        Route::get('{id}/review', [PayrollController::class, 'Review'])->name('review');
        Route::put('{id}/review', [PayrollController::class, 'markAsReviewed'])->name('review.submit');
        Route::get('calculate', [PayrollController::class, 'calculatePayroll'])->name('calculate');
    });

    Route::resource('payroll', PayrollController::class);

    // Tunjangan & Potongan
    Route::prefix('tunjangan')->name('tunjangan.')->group(function () {
        Route::post('/store/{id_payroll}', [TunjanganController::class, 'store'])->name('store');
        Route::put('/{id}', [TunjanganController::class, 'update'])->name('update');
        Route::delete('/{id}', [TunjanganController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('potongan')->name('potongan.')->group(function () {
        Route::post('/store/{id_payroll}', [PotonganController::class, 'store'])->name('store');
        Route::put('/{id}', [PotonganController::class, 'update'])->name('update');
        Route::delete('/{id}', [PotonganController::class, 'destroy'])->name('destroy');
    });
});
