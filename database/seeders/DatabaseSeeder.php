<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Jurager\Teams\Events\TeamMemberAdded;
use Jurager\Teams\Models\Team;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        Role::truncate();
        Permission::truncate();

        $admin = \App\Models\User::factory()->create([
            'full_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
        ]);

        Role::create(['name' => 'Super Admin']);
        $admin->assignRole('Super Admin');
        $user = \App\Models\User::factory()->create([
            'full_name' => 'User',
            'username' => 'user',
            'email' => 'user@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
        ]);
        $role = Role::create(['name' => 'Writer']);
        Permission::findOrCreate('post.*');
        Permission::findOrCreate('patient.list');
        Permission::findOrCreate('file.*');
        $role->givePermissionTo('post.*');
        $role->givePermissionTo('patient.list');
        $user->assignRole('Writer');
        $user->givePermissionTo('file.*');

        \App\Models\MasterData::factory()->create([
            'list_key' => 'task-status',
            'data' => json_encode([["label"=>"Potential Patient","value"=>"potential_patient","color"=>"#fff","background_color"=>"#5b3286"],["value"=>"insurance_pending","label"=>"Insurance Pending","color"=>"#000","background_color"=>"#c0e1f6"],["label"=>"New Patient","value"=>"new_patient","color"=>"#000","background_color"=>"#e8eaed"],["label"=>"New Supply Request","value"=>"new_supply_request","color"=>"#000","background_color"=>"#d0d2d5"],["label"=>"Urgent","value"=>"urgent","color"=>"#fff","background_color"=>"#b10202"],["label"=>"Pending","value"=>"pending","color"=>"#000","background_color"=>"#ffe59f"],["background_color"=>"#ffe59f","color"=>"#000","value"=>"renewal","label"=>"Renewal"],["label"=>"Code","value"=>"code","active"=>"1","color"=>"#fff","background_color"=>"#116743"],["label"=>"PAR pending","value"=>"par_pending","active"=>"1","color"=>"#000","background_color"=>"#c0e1f6"],["label"=>"PAR","value"=>"par","active"=>"1","color"=>"#fff","background_color"=>"#0953a8"],["background_color"=>"#0953a8","color"=>"#fff","label"=>"Kepro","value"=>"kepro"],["background_color"=>"#0953a8#0953a8","color"=>"#fff","value"=>"pending_kepro","label"=>"Pending Kepro"],["background_color"=>"#ffc8aa","color"=>"#000","value"=>"outbound_document","label"=>"Outbound document"],["label"=>"Review","value"=>"review","color"=>"#fff","background_color"=>"#116743"],["label"=>"Done","value"=>"done","color"=>"#fff","background_color"=>"#116743"],["value"=>"drop_off","label"=>"Dropoff","color"=>"#fff","background_color"=>"#ffad0d"],["value"=>"new_supply_request","label"=>"New Supply Request","color"=>"#000","background_color"=>"#e8eaed"],["value"=>"ready_to_bill","label"=>"Ready to bill","color"=>"#000","background_color"=>"#d4edbb"]]),
            'created_by' => 'admin',
        ]);

        \App\Models\Patient::factory()->count(30)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Re-enable foreign key checks
    }
}
