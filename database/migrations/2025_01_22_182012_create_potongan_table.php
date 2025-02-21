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
        Schema::create('potongan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_payroll')->constrained('payroll')->onDelete('cascade');
            $table->string('nama');
            $table->decimal('nominal', 8, 2);
            $table->boolean('status')->default(false); // True: Processed, False: Drafted
            $table->timestamps();

            $table->unique(['id_payroll', 'nama']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potongan');
    }
};
