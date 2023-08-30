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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->string('unify_name');
            $table->string('unify_number');
            $table->date('unify_active')->nullable();
            $table->integer('unify_status');
            $table->integer('sale_user');
            $table->integer('doctor_id')->nullable();
            $table->integer('unify_process');

            $table->boolean('unify_deleted');
            $table->time('unify_deleted_at')->nullable();;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
