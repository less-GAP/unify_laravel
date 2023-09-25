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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->index();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->date('dob')->nullable();
            $table->integer('gender')->default(0);
            $table->string('certificate')->nullable();

            $table->string('email')->index();
            $table->string('phone')->index();
            $table->string('address')->index();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            
            $table->text('note')->nullable();

            $table->integer('status')->nullable();
            $table->boolean('deleted')->default(0); // trashed
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->integer('updated_by')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
