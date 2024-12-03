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
            $table->integer('id_jabatan')->nullable()->unsigned();
            $table->integer('id_atasan')->nullable()->unsigned();
            $table->string('nama');
            $table->char('nik', 16)->unique()->nullable();
            $table->string('email')->unique();
            $table->char('npwp', 16)->unique()->nullable();
            $table->string('password');
            $table->string('no_telepon')->unique()->nullable();
            $table->char('jenis_kelamin', 1)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_perekrutan')->nullable();
            $table->string('agama')->nullable();
            $table->string('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten_kota')->nullable();
            $table->string('foto_profil')->nullable();
            $table->string('foto_ktp')->nullable();
            $table->string('foto_bpjs_kesehatan')->nullable();
            $table->string('foto_bpjs_ketenagakerjaan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Foreign keys
            // $table->foreign('id_jabatan')->references('id')->on('jabatan')->onDelete('set null');
            // $table->foreign('id_atasan')->references('id')->on('karyawans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
