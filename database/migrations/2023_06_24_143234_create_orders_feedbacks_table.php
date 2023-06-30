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
        Schema::create('orders_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id');
            $table->boolean('is_delivered');
            $table->text("description")->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_feedbacks');
    }
};
