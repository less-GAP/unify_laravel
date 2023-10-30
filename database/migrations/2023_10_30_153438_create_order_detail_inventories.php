<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_detail_inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('order_detail_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('inventory_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_detail_inventories');
    }
};
