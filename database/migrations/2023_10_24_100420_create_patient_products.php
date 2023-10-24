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
            $table->integer('patient_id');
            $table->date('delivery_date');
            $table->integer('amount');
            $table->integer('product_id');
            $table->text('product');
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
