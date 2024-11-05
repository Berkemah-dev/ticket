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
            $table->id();
            $table->unsignedBigInteger('id_customer');
            $table->unsignedBigInteger('id_event');
            $table->enum('status', ['pending', 'success', 'failed']);
            $table->string('snap_token');
            $table->string('invoice');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_customer')->references('id')->on('customers');
            $table->foreign('id_event')->references('id')->on('events');
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
