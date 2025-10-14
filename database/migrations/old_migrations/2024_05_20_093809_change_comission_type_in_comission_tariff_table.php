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
        Schema::table('commission_tariffs', function (Blueprint $table) {
            $table->double('commission_percent', 15, 2)->change();  // 15 total digits, 8 after decimal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_tariffs', function (Blueprint $table) {
            // Revert the column to its previous type (e.g., integer)
            $table->integer('commission_percent')->change();  // Or another appropriate type
        });
    }
};
