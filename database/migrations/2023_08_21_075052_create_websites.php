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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('icon')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->string('site_title')->nullable();
            $table->string('theme')->default('default');
            $table->text('description')->nullable();
            $table->longText('pages');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
        Schema::dropIfExists('themes');
    }
};
