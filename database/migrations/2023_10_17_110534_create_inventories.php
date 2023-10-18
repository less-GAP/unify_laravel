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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->date('date')->nullable();
            $table->string('created_by')->nullable();
            $table->integer('amount')->default(0);
            $table->string('status', 10)->nullable();
            $table->text('description')->nullable();
            $table->integer('order_id')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('trademark_id')->nullable();
            $table->integer('amount')->default(0);
            $table->date('expiration_date')->nullable();
            $table->integer('used')->default(0);
            $table->integer('remaining')->default(0);
            $table->integer('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('inventory_detail');
    }
};
