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
        Schema::create('widget_options', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('widget_id');
            $table->foreign('widget_id')->references('id')->on('widgets')
                ->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->string('key', 128);
            $table->string('value', 128);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_options');
    }
};
