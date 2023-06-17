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
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities')
                ->onDelete('RESTRICT')->onUpdate('RESTRICT');

            $table->string('address');
            $table->string('postal_code', 10);
            $table->string('recipient_name', 128);
            $table->string('recipient_phone', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
