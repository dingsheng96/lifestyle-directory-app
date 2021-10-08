<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new User())->getTable());

        // create super admin
        User::create([
            'name'                  =>  'Super Admin',
            'email'                 =>  'superadmin@bizboo.com',
            'password'              =>  'password',
            'status'                =>  User::STATUS_ACTIVE,
            'application_status'    =>  User::APPLICATION_STATUS_APPROVED,
            'email_verified_at'     =>  now()
        ])->assignRole(Role::ROLE_SUPER_ADMIN);

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
