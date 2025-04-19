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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign('categories_filter_id_foreign');
            $table->dropColumn(['filter_id', 'filter_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('filter_id')->nullable()->default(null);
            $table->foreign('filter_id')->references('id')->on('filters')
                ->onDelete('SET NULL')->onUpdate('RESTRICT');

            $table->enum('filter_type', ['inherit', 'none', 'filterId']);
        });
    }
};
