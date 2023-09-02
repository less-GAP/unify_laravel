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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->dateTime('deadline_at')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->integer('created_by'); // nguoi giao task
            $table->text('assignee_id')->nullable(); // nhung nguoi duoc nhan task
            $table->integer('patient_id')->nullable();
            $table->integer('task_tag_id')->nullable();
            $table->integer('task_priority_id')->default(0);

            $table->integer('deleted')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
