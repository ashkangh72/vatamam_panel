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
            $table->string('gateway')->default('payir');
            $table->string('name');
            $table->string('mobile');
            $table->unsignedBigInteger('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('SET NULL');

            $table->unsignedBigInteger('discount_id')->nullable();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');
            $table->unsignedBigInteger('discount_amount')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('SET NULL');
            $table->unsignedBigInteger('gateway_id')->nullable();
            $table->foreign('gateway_id')->references('id')->on('gateways')->onDelete('set null');
            $table->unsignedBigInteger('carrier_id')->nullable();
            $table->foreign('carrier_id')->references('id')->on('carriers')->onDelete('set null');
            $table->string('postal_code');
            $table->text('address');
            $table->string('description', 1000)->nullable();

            $table->unsignedBigInteger('shipping_cost');
            $table->unsignedBigInteger('price');
            $table->string('status')->default('unpaid');

            $table->string('shipping_status')->default('pending');

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
