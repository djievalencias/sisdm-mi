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
        Schema::create('distribusi_pengumuman', function (Blueprint $table) {
            $table->foreignId('id_pengumuman')->constrained('pengumuman')->onDelete('cascade');
            $table->foreignId('id_departemen')->constrained('departemen')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_pengumuman');
    }
};
