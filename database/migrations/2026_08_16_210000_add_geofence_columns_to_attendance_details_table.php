<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->decimal('distance_m', 8, 2)->nullable()->after('address');
            $table->boolean('is_within_radius')->nullable()->after('distance_m');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->dropColumn(['distance_m', 'is_within_radius']);
        });
    }
};
