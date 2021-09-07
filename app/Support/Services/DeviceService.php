<?php

namespace App\Support\Services;

use App\Models\DeviceSetting;
use App\Support\Services\BaseService;

class DeviceService extends BaseService
{
    public function __construct()
    {
        parent::__construct(DeviceSetting::class);
    }

    public function store()
    {
        $this->model->device_id                  =   $this->request->get('device_id');
        $this->model->device_os                  =   $this->request->get('device_os');
        $this->model->push_messaging_token       =   $this->request->get('push_messaging_token');
        $this->model->enable_push_messaging      =   $this->request->get('enable_push_messaging');
        $this->model->enable_notification_sound  =   $this->request->get('enable_notification_sound');
        $this->model->last_activated_at          =   now();

        if ($this->model->isDirty()) {

            $this->model->save();
        }

        return $this;
    }

    public function updateDeviceLastActivationDate()
    {
        $this->model->last_activated_at = now();
        $this->model->save();

        return $this;
    }
}
