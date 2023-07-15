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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('discount_id')->nullable();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');

            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('set null');

            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('price');
            $table->unsignedBigInteger('discount_amount')->nullable();
            $table->unsignedBigInteger('discount_price')->nullable();
            $table->string('description')->nullable();
            $table->string('post_track_code', 32)->nullable();
            $table->string('post_description')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('shipping_status', 64)->nullable();
            $table->unsignedBigInteger('shipping_cost')->nullable();
            $table->boolean('is_satisfied')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
