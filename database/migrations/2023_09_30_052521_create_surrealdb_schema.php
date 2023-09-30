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
        \DB::connection('realtime')->statement('USE NS '.env('DB_NAMESPACE_REALTIME', 'unify').' DB '.env('DB_DATABASE_REALTIME', 'unify').';');
        \DB::connection('realtime')->statement('DEFINE TABLE versions SCHEMALESS;');
        \DB::connection('realtime')->statement('DEFINE TABLE versions SCHEMALESS;');
        \DB::connection('realtime')->statement('DEFINE INDEX versionKeyIndex ON TABLE versions COLUMNS key UNIQUE;');
        \DB::connection('realtime')->statement('DEFINE TABLE statistics SCHEMALESS;');
        \DB::connection('realtime')->statement('DEFINE FIELD key ON TABLE statistics TYPE string;');
        \DB::connection('realtime')->statement('DEFINE INDEX key ON TABLE statistics COLUMNS key UNIQUE;');
        \DB::connection('realtime')->statement('DEFINE USER viewer ON ROOT PASSWORD \'123456\' ROLES VIEWER;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
