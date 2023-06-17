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
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('province_id');
            $table->foreign('province_id')->references('id')->on('provinces')
                ->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->string('name');
            $table->unsignedBigInteger('ordering');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
