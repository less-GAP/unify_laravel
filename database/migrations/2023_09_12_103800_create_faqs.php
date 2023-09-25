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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->unique();
            $table->string('type')->nullable();
            $table->longText('description')->nullable();
            $table->string('title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('status')->default('A');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
