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
        Schema::create('tunjangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_payroll')->unique()->constrained('payroll')->onDelete('cascade');
            $table->string('nama');
            $table->decimal('nominal', 6, 2);
            $table->boolean('status')->default(false); // True: Processed, False: Drafted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunjangan');
    }
};
