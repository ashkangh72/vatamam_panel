<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_checkout_transactions', function (Blueprint $table) {
            $table->id();
            $table->text('reference_number');
            $table->text('track_id');
            $table->text('owner_code');
            $table->text('request_channel');
            $table->text('type');
            $table->text('source_iban');
            $table->text('destination_iban');
            $table->text('total_amount');
            $table->text('created_at_jibit');
            $table->text('updated_at_jibit');
            $table->longText('records');
            $table->text('status');
            $table->foreignId('expert_checkout_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_checkout_transactions');
    }
};
