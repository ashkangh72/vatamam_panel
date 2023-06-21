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
        Schema::create('refunded_order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_auction_id');
            $table->string('picture');
            $table->enum('reason', ['stricken', 'change_color_size', 'wrong_color_size']);
            $table->enum('status', ['waiting', 'waiting_to_receive', 'received', 'rejected'])->default('waiting');
            $table->timestamps();

            $table->foreign('order_auction_id')->references('id')->on('order_auction')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunded_order_items');
    }
};
