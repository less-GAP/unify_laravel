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
            $table->integer('task_process')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->unsignedBigInteger('created_by'); // nguoi giao task
            $table->text('assignees')->nullable(); // nhung nguoi duoc nhan task
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->integer('task_tag_id')->nullable();
            $table->integer('task_priority_id')->default(0);

            $table->integer('deleted')->default(0);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');

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
