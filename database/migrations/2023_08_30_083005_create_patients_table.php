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

            $table->date('unify_active')->nullable();
            $table->integer('unify_status');
            $table->integer('unify_process');

            //data
            $table->string('full_name');
            $table->string('last_name');
            $table->string('first_name');
            $table->date('dob')->nullable();
            $table->text('height')->nullable();
            $table->text('weight')->nullable();
            $table->integer('gender')->default(0);

            $table->text('insurance_coverages')->nullable();

            // contact
            $table->string('email');
            $table->string('phone');
            $table->text('street');
            $table->text('apt');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('route');
            $table->string('sub_r');

            $table->string('supplies'); // medical_supplies
            $table->text('products')->nullable();

            $table->text('note');
            $table->text('unify_data'); // merge weight, height and name of doctor the patient chooses

            // confirm by doctor
            $table->integer('doctor_id')->nullable();
            $table->integer('doctor_status')->default(0);
            $table->text('doctor_comment')->nullable();

            // on approve to a new patient of Unify, will have services and need improve status
            $table->text('service_dates')->nullable();
            $table->integer('need_improve')->default(0);

            $table->boolean('unify_deleted');
            $table->time('unify_deleted_at')->nullable();;
            $table->integer('unify_deleted_by')->nullable();;

            $table->integer('sale_user'); // created_by
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
