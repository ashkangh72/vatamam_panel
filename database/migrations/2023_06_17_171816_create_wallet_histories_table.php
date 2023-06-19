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
        Schema::create('wallet_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets')
                ->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->unsignedTinyInteger('type');
            $table->boolean('success')->default(true);
            $table->unsignedBigInteger('amount');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_histories');
    }
};
