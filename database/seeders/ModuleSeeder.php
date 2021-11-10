<?php

namespace Database\Seeders;

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
        collect($this->getData())->sortBy('name')->each(function ($data) {
            Module::firstOrCreate(
                [
                    'name'          =>  $data['name']
                ],
                [
                    'display'       =>  $data['display'],
                    'description'   =>  $data['description']
                ]
            );
        });
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
            ['name' => 'career', 'display' => 'Career', 'description' => 'Career Module'],
            ['name' => 'banner', 'display' => 'Banner', 'description' => 'Banner Module'],
            ['name' => 'application', 'display' => 'Application', 'description' => 'Application Module'],
            ['name' => 'review', 'display' => 'Review', 'description' => 'Review Module'],
        ];
    }
}
