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
        Schema::create('refunded_order_auction', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('refunded_order_id');
            $table->foreign('refunded_order_id')->references('id')->on('refunded_orders')->onDelete('cascade');

            $table->unsignedBigInteger('auction_id')->nullable();
            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('set null');

            $table->integer('quantity');
            $table->enum('reason', ['fracture', 'intact', 'incompatibility']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunded_order_auction');
    }
};
