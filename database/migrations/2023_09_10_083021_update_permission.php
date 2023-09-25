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
        Schema::table('permissions', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('status')->index()->default('active');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->string('status')->index()->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('status');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('status');
        });
    }
};
