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
        Schema::create('chat_inquiries', function (Blueprint $table) {
            $table->id();
            $table->integer('buyer_id');
            $table->integer('seller_id');
            $table->integer('product_id');
            $table->integer('initial_msg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_inquiries');
    }
};
