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
        Schema::create('auctions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku', 128)->unique();

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->unsignedBigInteger('originality_id');
            $table->foreign('originality_id')->references('id')->on('originality')
                ->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->unsignedBigInteger('historical_period_id');
            $table->foreign('historical_period_id')->references('id')->on('historical_periods')
                ->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->string('title');
            $table->unsignedTinyInteger('condition');
            $table->unsignedTinyInteger('timezone');
            $table->set('material', ['wooden', 'metal', 'breakable', 'fiber', 'artificial', 'other']);
            $table->string('other_material', 128);
            $table->text('description')->nullable()->default(null);
            $table->string('picture', 128)->nullable()->default(null);
            $table->unsignedInteger('base_price');
            $table->unsignedTinyInteger('increase_factor_percent');
            $table->unsignedInteger('quick_sale_price');
            $table->unsignedInteger('partner_quick_sale_price')->nullable()->default(null);
            $table->boolean('guaranteed')->default(0);
            $table->unsignedTinyInteger('shipping_method');
            $table->boolean('undertaking_shipping_cost');
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->boolean('is_ended')->default(0);
            $table->timestamp('end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
