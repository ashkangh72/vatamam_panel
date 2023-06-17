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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable()->default(null);

            $table->unsignedBigInteger('filter_id')->nullable()->default(null);
            $table->foreign('filter_id')->references('id')->on('filters')
                ->onDelete('SET NULL')->onUpdate('RESTRICT');

            $table->string('title');
            $table->string('slug');
            $table->unsignedBigInteger('ordering');
            $table->string('meta_title');
            $table->text('meta_description')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->enum('filter_type', ['inherit', 'none', 'filterId']);
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('SET NULL')->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
