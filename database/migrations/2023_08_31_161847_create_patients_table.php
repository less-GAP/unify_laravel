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

            $table->dateTime('unify_active')->nullable();
            $table->integer('unify_status')->nullable();
            $table->integer('unify_process')->nullable();
            $table->integer('unify_task_status')->nullable();

            //data
            $table->string('full_name')->index();
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->date('dob')->nullable();
            $table->text('height')->nullable();
            $table->text('weight')->nullable();
            $table->integer('gender')->default(0);

            $table->text('insurance_coverages')->nullable();

            // contact
            $table->string('email')->index();
            $table->string('phone')->index();
            $table->text('street')->nullable();
            $table->text('apt')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('route')->nullable();
            $table->string('sub_r')->nullable();

            $table->string('supplies')->nullable(); // medical_supplies
            $table->text('products')->nullable();

            $table->text('note')->nullable();
            $table->text('unify_data')->nullable(); // merge weight, height and name of doctor the patient chooses

            // confirm by doctor
            $table->integer('doctor_id')->nullable();
            $table->integer('doctor_status')->default(0);
            $table->text('doctor_comment')->nullable();

            // on approve to a new patient of Unify, will have services and need improve status
            $table->text('service_dates')->nullable();
            $table->integer('need_improve')->default(0);

            $table->boolean('unify_deleted')->nullable();
            $table->dateTime('unify_deleted_at')->nullable();
            $table->integer('unify_deleted_by')->nullable();

            $table->integer('sale_user')->nullable(); // created_by
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
