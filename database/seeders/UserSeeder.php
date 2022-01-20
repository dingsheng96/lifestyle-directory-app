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
        User::firstOrCreate([
            'email' => 'superadmin@bizboo.com',
        ], [
            'name'                  =>  'Super Admin',
            'email'                 =>  'superadmin@bizboo.com',
            'password'              =>  'password',
            'status'                =>  User::STATUS_ACTIVE,
            'application_status'    =>  User::APPLICATION_STATUS_APPROVED,
            'type'                  =>  User::USER_TYPE_ADMIN
        ])->assignRole(Role::ROLE_SUPER_ADMIN);
    }
}
