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
        Schema::table('total_affiliates', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->after('affiliate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('total_affiliates', function (Blueprint $table) {
            //
        });
    }
};
