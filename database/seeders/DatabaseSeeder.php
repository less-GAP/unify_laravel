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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        \App\Models\User::truncate();
        \App\Models\Patient::truncate();
        \App\Models\MasterData::truncate();
        
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

        // '[{"label":"Potential Patient","value":"potential_patient","color":"#fff","background_color":"#5b3286"},{"value":"insurance_pending","label":"Insurance Pending","color":"#000","background_color":"#c0e1f6"},{"label":"New Patient","value":"new_patient","color":"#000","background_color":"#e8eaed"},{"label":"New Supply Request","value":"new_supply_request","color":"#000","background_color":"#d0d2d5"},{"label":"Urgent","value":"urgent","color":"#fff","background_color":"#b10202"},{"label":"Pending","value":"pending","color":"#000","background_color":"#ffe59f"},{"background_color":"#ffe59f","color":"#000","value":"renewal","label":"Renewal"},{"label":"Code","value":"code","active":"1","color":"#fff","background_color":"#116743"},{"label":"PAR pending","value":"par_pending","active":"1","color":"#000","background_color":"#c0e1f6"},{"label":"PAR","value":"par","active":"1","color":"#fff","background_color":"#0953a8"},{"background_color":"#0953a8","color":"#fff","label":"Kepro","value":"kepro"},{"background_color":"#0953a8#0953a8","color":"#fff","value":"pending_kepro","label":"Pending Kepro"},{"background_color":"#ffc8aa","color":"#000","value":"outbound_document","label":"Outbound document"},{"label":"Review","value":"review","color":"#fff","background_color":"#116743"},{"label":"Done","value":"done","color":"#fff","background_color":"#116743"},{"value":"drop_off","label":"Dropoff","color":"#fff","background_color":"#ffad0d"},{"value":"new_supply_request","label":"New Supply Request","color":"#000","background_color":"#e8eaed"},{"value":"ready_to_bill","label":"Ready to bill","color":"#000","background_color":"#d4edbb"}]
        \App\Models\MasterData::factory()->create([
            'list_key' => 'patient-status',
            'data' => '',
            'created_by' => 'admin',
        ]);

        \App\Models\Patient::factory()->count(20)->create();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
