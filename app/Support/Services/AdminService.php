<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Support\Services\BaseService;

class AdminService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function store()
    {
        $this->model->name      =   $this->request->get('name');
        $this->model->email     =   $this->request->get('email');
        $this->model->status    =   $this->request->get('status', User::STATUS_ACTIVE);
        $this->model->password  =   !empty($this->request->get('password'))
            ? Hash::make($this->request->get('password'))
            : $this->model->password;
        $this->model->type      =   User::USER_TYPE_ADMIN;

        if (!$this->model->exists) { // new Admin

            $this->model->email_verified_at = now();
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        $this->assignRole();

        return $this;
    }

    public function assignRole()
    {
        $role = Role::where('id', $this->request->get('role'))->first();

        $this->model->assignRole($role->name);
    }
}
