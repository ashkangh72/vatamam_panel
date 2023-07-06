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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(1)->after('price');
            $table->unsignedBigInteger('discount_price')->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('discount_price');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('unpaid')->after('price');
        });
    }
};
