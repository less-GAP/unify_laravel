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
        activity()->disableLogging();

        \App\Models\User::truncate();
        \App\Models\Patient::truncate();
        \App\Models\MasterData::truncate();
        Role::truncate();
        Permission::truncate();
        \App\Models\Customer::truncate();
        \App\Models\Customer::factory()->count(10)->create();
        $admin = \App\Models\User::factory()->create([
            'full_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@unifymed.net',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => 'active'
        ]);
        $seller = \App\Models\User::factory()->create([
            'full_name' => 'Nguyen Thi Hong',
            'username' => 'seller',
            'email' => 'seller@unifymed.net',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => 'active'
        ]);
        $seller1 = \App\Models\User::factory()->create([
            'full_name' => 'Nguyen Van A',
            'username' => 'seller1',
            'email' => 'seller1@unifymed.net',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => 'active'
        ]);
        $seller2 = \App\Models\User::factory()->create([
            'full_name' => 'Nguyen Van B',
            'username' => 'seller2',
            'email' => 'seller2@unifymed.net',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => 'active'
        ]);
        $seller3 = \App\Models\User::factory()->create([
            'full_name' => 'Tran Van B',
            'username' => 'seller3',
            'email' => 'seller3@unifymed.net',
            'email_verified_at' => Carbon::now(),
            'password' => \Hash::make('123456'),
            'deleted' => 0,
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => 'active'
        ]);

        $role_admin = Role::create(['name' => 'Admin']);

        $role_seller_manager = Role::create(['name' => 'Seller Manager']);
        $role_seller = Role::create(['name' => 'Seller']);

        $role_staff_manager = Role::create(['name' => 'Staff Manager']);
        $role_staff = Role::create(['name' => 'Staff']);

        Permission::findOrCreate('*');
        Permission::findOrCreate('Patient.*');
        Permission::findOrCreate('Patient.filter.seller');
        Permission::findOrCreate('Patient.filter.doctor');
        Permission::findOrCreate('Patient.list');
        Permission::findOrCreate('Patient.view');
        Permission::findOrCreate('Patient.approve');
        Permission::findOrCreate('Patient.create');
        Permission::findOrCreate('Patient.edit');
        Permission::findOrCreate('Patient.delete');

        Permission::findOrCreate('Doctor.*');
        Permission::findOrCreate('Doctor.list');
        Permission::findOrCreate('Doctor.create');
        Permission::findOrCreate('Doctor.edit');
        Permission::findOrCreate('Doctor.delete');

        Permission::findOrCreate('User.*');
        Permission::findOrCreate('User.list');
        Permission::findOrCreate('User.create');
        Permission::findOrCreate('User.edit');
        Permission::findOrCreate('User.delete');

        Permission::findOrCreate('Task.*');
        Permission::findOrCreate('Task.list');
        Permission::findOrCreate('Task.create');
        Permission::findOrCreate('Task.assign');
        Permission::findOrCreate('Task.working');
        Permission::findOrCreate('Task.review');
        Permission::findOrCreate('Task.delete');

        Permission::findOrCreate('Log.list');

        Permission::findOrCreate('File.*');

        Permission::findOrCreate('Seller');
        Permission::findOrCreate('Seller Manager');
        Permission::findOrCreate('Admin');

        // $role_seller->givePermissionTo('post.*');
        $role_seller->givePermissionTo(['Seller', 'patient.list','patient.view', 'patient.create', 'patient.edit', 'task.list', 'task.working', 'file.*']);
        $role_seller_manager->givePermissionTo('Seller Manager', 'task.*', 'patient.list','patient.view', 'patient.delete', 'patient.filter.doctor', 'patient.filter.seller', 'patient.create', 'patient.edit', 'file.*', 'user.*');
        $role_admin->givePermissionTo('*');


        $admin->assignRole('Admin');
        $seller->assignRole('Seller Manager');
        $seller1->assignRole('Seller');
        $seller2->assignRole('Seller');
        $seller3->assignRole('Seller');


        \App\Models\MasterData::factory()->create([
            'list_key' => 'task-status',
            'data' => json_encode([["label" => "Potential Patient", "value" => "potential_patient", "color" => "#fff", "background_color" => "#5b3286"], ["value" => "insurance_pending", "label" => "Insurance Pending", "color" => "#000", "background_color" => "#c0e1f6"], ["label" => "New Patient", "value" => "new_patient", "color" => "#000", "background_color" => "#e8eaed"], ["label" => "New Supply Request", "value" => "new_supply_request", "color" => "#000", "background_color" => "#d0d2d5"], ["label" => "Urgent", "value" => "urgent", "color" => "#fff", "background_color" => "#b10202"], ["label" => "Pending", "value" => "pending", "color" => "#000", "background_color" => "#ffe59f"], ["background_color" => "#ffe59f", "color" => "#000", "value" => "renewal", "label" => "Renewal"], ["label" => "Code", "value" => "code", "active" => "1", "color" => "#fff", "background_color" => "#116743"], ["label" => "PAR pending", "value" => "par_pending", "active" => "1", "color" => "#000", "background_color" => "#c0e1f6"], ["label" => "PAR", "value" => "par", "active" => "1", "color" => "#fff", "background_color" => "#0953a8"], ["background_color" => "#0953a8", "color" => "#fff", "label" => "Kepro", "value" => "kepro"], ["background_color" => "#0953a8#0953a8", "color" => "#fff", "value" => "pending_kepro", "label" => "Pending Kepro"], ["background_color" => "#ffc8aa", "color" => "#000", "value" => "outbound_document", "label" => "Outbound document"], ["label" => "Review", "value" => "review", "color" => "#fff", "background_color" => "#116743"], ["label" => "Done", "value" => "done", "color" => "#fff", "background_color" => "#116743"], ["value" => "drop_off", "label" => "Dropoff", "color" => "#fff", "background_color" => "#ffad0d"], ["value" => "new_supply_request", "label" => "New Supply Request", "color" => "#000", "background_color" => "#e8eaed"], ["value" => "ready_to_bill", "label" => "Ready to bill", "color" => "#000", "background_color" => "#d4edbb"]]),
            'created_by' => 'admin',
        ]);
        \App\Models\MasterData::factory()->create([
            'list_key' => 'insurance',
            'data' => json_encode([
                [
                    'value' => 'medicaid',
                    'label' => 'Medicaid',
                    'status' => 1,
                ],
                [
                    'value' => 'medicare_a',
                    'label' => 'Medicare A',
                    'status' => 1,
                ],
                [
                    'value' => 'medicare_b',
                    'label' => 'Medicare B',
                    'status' => 1,
                ],
                [
                    'value' => 'hcbs_elderly_blind_&_disabled_waiver',
                    'label' => 'HCBS Elderly, Blind & Disabled Waiver',
                    'status' => 1,
                ],
                [
                    'value' => 'specified_low_income_medicare_beneficiary',
                    'label' => 'Specified Low-Income Medicare Beneficiary',
                    'status' => 1,
                ],
                [
                    'value' => 'behavioral_health_benefits',
                    'label' => 'Behavioral Health Benefits',
                    'status' => 1,
                ],
                [
                    'value' => 'bha_benefit_plan',
                    'label' => 'BHA Benefit Plan',
                    'status' => 1,
                ],
            ]),
            'created_by' => 'admin',
        ]);
        \App\Models\Doctor::factory()->count(5)->create();
        \App\Models\Patient::factory()->count(50)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        activity()->enableLogging();


        // Re-enable foreign key checks
    }
}
