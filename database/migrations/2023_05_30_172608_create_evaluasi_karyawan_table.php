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
        Schema::create('evaluasi_performa_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_karyawan')->constrained('karyawan')->onDelete('cascade');
            $table->foreignId('id_payroll')->constrained('payroll')->onDelete('cascade');
            $table->foreignId('metric_id')->nullable()->constrained('metrics')->onDelete('set null');
            $table->date('tanggal');
            $table->json('score')->nullable();
            $table->unique(['id_karyawan', 'id_payroll', 'metric_id', 'tanggal'], 'employee_payroll_metric_date_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi_performa_karyawan');
    }
};
