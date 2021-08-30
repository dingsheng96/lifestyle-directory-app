<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Support\Services\BaseService;

class RoleService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

    public function store()
    {
        $this->model->name          =   $this->request->get('name');
        $this->model->description   =   $this->request->get('description');
        $this->model->guard_name    =   config('auth.default.guard', 'web');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->storePermissions();

        return $this;
    }

    public function storePermissions()
    {
        $permissions = $this->request->get('permissions');

        if (!empty($permissions)) {

            $this->model->syncPermissions($permissions);
        }

        return $this;
    }
}
