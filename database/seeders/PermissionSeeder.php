<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect($this->getData())
            ->sortBy('name')
            ->each(function ($data, $key) {
                Permission::firstOrCreate(
                    [
                        'name'          =>  $data['name'],
                        'guard_name'    =>  $data['guard_name'],
                    ],
                    [
                        'display'       =>  $data['display'],
                        'description'   =>  $data['description'],
                        'module_id'     =>  $data['module_id'],
                        'action'        =>  $data['action'],
                    ]
                );
            });
    }

    public function getData()
    {
        $modules = Module::orderBy('name', 'asc')->get();

        $actions = ['create', 'read', 'update', 'delete'];

        $all_data = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $all_data[] = [
                    'name'          =>  $module->name . '.' . $action,
                    'guard_name'    =>  config('auth.defaults.guard'),
                    'display'       =>  Str::title($action) . ' ' . $module->display,
                    'description'   =>  Str::title($action) . ' ' . $module->display,
                    'module_id'     =>  $module->id,
                    'action'        =>  $action,
                ];
            }
        }

        return $all_data;
    }
}
