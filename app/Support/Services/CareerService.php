<?php

namespace App\Support\Services;

use App\Models\Career;
use App\Support\Services\BaseService;

class CareerService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Career::class);
    }

    public function store()
    {
        $this->model->branch_id     =   $this->request->get('merchant');
        $this->model->position      =   $this->request->get('position');
        $this->model->description   =   $this->request->get('description');
        $this->model->benefit       =   $this->request->get('benefit');
        $this->model->min_salary    =   $this->request->get('min_salary');
        $this->model->max_salary    =   $this->request->get('max_salary');
        $this->model->show_salary   =   $this->request->has('show_salary');
        $this->model->status        =   $this->request->get('status', Career::STATUS_PUBLISH);

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }
}