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
        Schema::create('grup', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_departemen')->nullable()->constrained('departemen')->onDelete('set null');
            $table->string('nama');
            $table->timestamps();

            $table->unique(['id_departemen', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup');
    }
};
