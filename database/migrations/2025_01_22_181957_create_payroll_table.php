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
            $table->decimal('gaji_pokok', 7, 2)->default(0);
            $table->decimal('upah_lembur', 5, 2)->default(0);
            $table->decimal('gaji_tgl_merah', 5, 2)->default(0);
            $table->decimal('upah_lembur_tgl_merah', 5, 2)->default(0);
            $table->decimal('bpjs_kes_perusahaan', 5, 2)->default(0);
            $table->decimal('bpjs_jkk_perusahaan', 5, 2)->default(0);
            $table->decimal('bpjs_jht_perusahaan', 5, 2)->default(0);
            $table->decimal('bpjs_jkm_perusahaan', 5, 2)->default(0);
            $table->decimal('bpjs_jp_perusahaan', 5, 2)->default(0);
            $table->decimal('bpjs_kes_user', 5, 2)->default(0);
            $table->boolean('is_reviewed')->default(false);
            $table->boolean('status')->default(false); // True: Paid, False: Pending
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
