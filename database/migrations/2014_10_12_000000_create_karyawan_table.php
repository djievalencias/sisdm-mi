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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jabatan')->nullable()->constrained('jabatan')->onDelete('set null');
            $table->foreignId('id_atasan')->nullable()->constrained('karyawan')->onDelete('set null');
            $table->string('nama');
            $table->char('nik', 16)->unique();
            $table->string('email')->unique();
            $table->char('npwp', 16)->unique();
            $table->string('password');
            $table->string('no_telepon')->unique();
            $table->enum('jenis_kelamin', ['P', 'L'])->default('L'); // L for male, P for female
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->date('tanggal_perekrutan');
            $table->date('tanggal_pemutusan_kontrak')->nullable();
            $table->string('agama');
            $table->string('alamat');
            $table->string('rt');
            $table->string('rw');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kabupaten_kota');
            $table->string('foto_profil')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_bpjs_kesehatan')->nullable();
            $table->string('foto_bpjs_ketenagakerjaan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_remote')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
