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
        Schema::create('patient_products', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_value')->nullable();
            $table->string('delivery_type')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('product_id')->nullable();
            $table->text('product')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_products');
    }
};
