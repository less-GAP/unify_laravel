<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::truncate();
        \App\Models\User::factory()->create([
            'full_name' => 'Admin',
            'username' => 'admin',
            'role' => 'admin',
            'email' => 'admin@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
        ]);

        \App\Models\Patient::truncate();
        \App\Models\Patient::factory()->count(20)->create();
    }
}
