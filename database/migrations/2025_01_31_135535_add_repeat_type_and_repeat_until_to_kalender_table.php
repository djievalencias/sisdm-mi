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
        Schema::table('kalender', function (Blueprint $table) {
            $table->enum('repeat_type', ['never', 'weekly', 'monthly', 'yearly'])->default('never')->after('tipe');
            $table->date('repeat_until')->nullable()->after('repeat_type')->after('repeat_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kalender', function (Blueprint $table) {
            $table->dropColumn(['repeat_type', 'repeat_until']);
        });
    }
};
