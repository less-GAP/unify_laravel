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
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->string('event_name')->index();
            $table->string('status')->default('active')->index();
            $table->longText('data')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        Schema::create('event_notification', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('event_index')->index();
            $table->string('event_name')->index();
            $table->string('notification_channel');
            $table->string('notification_model');
            $table->string('notification_model_id');
            $table->string('status')->default('waiting')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_settings');
        Schema::dropIfExists('event_notification');
    }
};
