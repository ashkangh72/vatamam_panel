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
        Schema::create('widget_option_categories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('widget_option_id');
            $table->foreign('widget_option_id')->references('id')->on('widget_options')
                ->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_categories_options');
    }
};
