<?php

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::statement('TRUNCATE TABLE ' . (new Module())->getTable());

        collect($this->getData())->sortBy('name')->each(function ($data) {
            Module::create([
                'name'          =>  $data['name'],
                'display'       =>  $data['display'],
                'description'   =>  $data['description']
            ]);
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function getData()
    {
        return [
            ['name' => 'locale', 'display' => 'Locale', 'description' => 'Locale Module'],
            ['name' => 'role', 'display' => 'Role', 'description' => 'Role Module'],
            ['name' => 'member', 'display' => 'Member', 'description' => 'Member Module'],
            ['name' => 'merchant', 'display' => 'Merchant', 'description' => 'Merchant Module'],
            ['name' => 'admin', 'display' => 'Admin', 'description' => 'Admin Module'],
            ['name' => 'category', 'display' => 'Category', 'description' => 'Category Module'],
            ['name' => 'activity_log', 'display' => 'Activity Log', 'description' => 'Activity Log Module'],
            ['name' => 'career', 'display' => 'Career', 'description' => 'Career Module'],
        ];
    }
}
