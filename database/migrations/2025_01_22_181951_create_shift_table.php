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
        Schema::create('shift', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->boolean('senin')->default(false);
            $table->boolean('selasa')->default(false);
            $table->boolean('rabu')->default(false);
            $table->boolean('kamis')->default(false);
            $table->boolean('jumat')->default(false);
            $table->boolean('sabtu')->default(false);
            $table->boolean('minggu')->default(false);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift');
    }
};
