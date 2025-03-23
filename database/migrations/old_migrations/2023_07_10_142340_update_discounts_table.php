<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn([
                'least_products_count',
                'only_first_purchase',
                'not_discount_products',
                'include_type',
                'exclude_type',
                'amount'
            ]);
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->unsignedBigInteger('amount')->after('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->integer('least_products_count')->nullable();
            $table->boolean('only_first_purchase')->default(false);
            $table->boolean('not_discount_products')->default(false);
            $table->enum('include_type', ['all', 'category', 'product'])->default('all');
            $table->enum('exclude_type', ['none', 'category', 'product'])->default('none');
            $table->decimal('amount', 15);
        });
    }
}
