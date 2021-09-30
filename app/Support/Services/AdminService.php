<?php

namespace App\Support\Services;

use App\Models\Role;
use App\Models\User;
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
        $this->model->password  =   $this->request->get('password');
        $this->model->type      =   User::USER_TYPE_ADMIN;

        if (!$this->model->exists) { // new Admin

            $this->model->email_verified_at = now();
        }

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }

    public function assignRole()
    {
        $role = Role::where('id', $this->request->get('role'))->first();

        $this->model->syncRoles([$role->name]);

        if ($role->generate_referral) {

            $this->generateReferralCode();
        }

        return $this;
    }

    private function generateReferralCode()
    {
        $referral_code = random_int(100000, 999999);

        while (User::admin()->where('referral_code', $referral_code)->exists()) {

            $referral_code = random_int(100000, 999999);
        }

        $this->model->referral_code = $referral_code;

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }
}
