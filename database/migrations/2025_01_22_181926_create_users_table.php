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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jabatan')->nullable()->constrained('jabatan')->onDelete('set null');
            $table->foreignId('id_atasan')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama');
            $table->char('nik', 16)->unique()->default('');
            $table->string('email')->unique();
            $table->char('npwp', 16)->unique()->default('');
            $table->string('password')->default('');
            $table->string('no_telepon')->unique()->default('');
            $table->enum('jenis_kelamin', ['P', 'L'])->default('L'); // L for male, P for female
            $table->string('tempat_lahir')->default('');
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_perekrutan')->nullable();
            $table->date('tanggal_pemutusan_kontrak')->nullable();
            $table->string('agama')->default('');
            $table->string('alamat')->default('');
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->default('');
            $table->string('kecamatan')->default('');
            $table->string('kabupaten_kota')->default('');
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
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
