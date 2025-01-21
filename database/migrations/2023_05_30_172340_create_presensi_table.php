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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->onDelete('cascade');
            $table->Date('tanggal');
            $table->enum('status', ['on_time', 'late', 'missed'])->default('missed');
            $table->time('scan_in');
            $table->time('scan_out');
            $table->time('scan_lembur')->nullable();
            $table->decimal('hari_kerja', 4, 2);
            $table->decimal('jumlah_jam_lembur', 4, 2)->nullable();
            $table->boolean('is_tanggal_merah')->default(false);
            $table->boolean('is_manually_filled')->default(false); // when employees take attendance themselves
            $table->float('koordinat_x')->nullable();
            $table->float('koordinat_y')->nullable();
            $table->unique(['id_karyawan', 'tanggal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
