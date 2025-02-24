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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->Date('tanggal');
            $table->boolean('status')->default(false);
            $table->decimal('hari_kerja', 4, 2);
            $table->decimal('jumlah_jam_lembur', 4, 2)->nullable();
            $table->boolean('is_tanggal_merah')->default(false);
            $table->unique(['id_user', 'tanggal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
