<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payroll', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_payroll');
            $table->decimal('gaji_pokok', 9, 2)->default(0);
            $table->decimal('upah_lembur', 8, 2)->default(0);
            $table->decimal('gaji_tgl_merah', 8, 2)->default(0);
            $table->decimal('upah_lembur_tgl_merah', 8, 2)->default(0);
            $table->decimal('iuran_bpjs_kantor', 8, 2)->default(0);
            $table->decimal('iuran_bpjs_karyawan', 8, 2)->default(0);
            $table->decimal('take_home_pay', 10, 2)->default(0);
            $table->boolean('is_reviewed')->default(false);
            $table->boolean('status_pembayaran')->default(false);
            $table->unique(['id_user', 'tanggal_payroll']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll');
    }
};
