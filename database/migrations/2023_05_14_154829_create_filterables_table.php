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
        Schema::create('filterables', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('filter_id');
            $table->foreign('filter_id')->references('id')->on('filters')
                ->onDelete('CASCADE')->onUpdate('RESTRICT');

            $table->unsignedBigInteger('filterable_id');
            $table->string('filterable_type');
            $table->integer('ordering')->nullable()->default(null);
            $table->string('separator')->nullable()->default(null);
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filterables');
    }
};
