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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', total: 10, places: 2);
            $table->string('currency', length: 50);
            $table->ipAddress('ip');
            $table->unsignedBigInteger('device_id');
            $table->timestamp('timestamp');
            $table->unsignedBigInteger('merchant_id');
            $table->enum('verdict', ['ok', 'review', 'blocked'])->nullable();
            $table->json('reasons')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
