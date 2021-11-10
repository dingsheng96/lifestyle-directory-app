<?php

namespace App\Support\Services;

use App\Models\Career;
use Illuminate\Support\Facades\Auth;
use App\Support\Services\BaseService;

class CareerService extends BaseService
{
    public function __construct()
    {
        parent::__construct(Career::class);
    }

    public function store()
    {
        $this->model->branch_id     =   $this->request->get('merchant', Auth::id());
        $this->model->position      =   $this->request->get('position');
        $this->model->about         =   $this->request->get('about');
        $this->model->description   =   $this->request->get('description');
        $this->model->benefit       =   $this->request->get('benefit');
        $this->model->min_salary    =   $this->request->get('min_salary');
        $this->model->max_salary    =   $this->request->get('max_salary');
        $this->model->show_salary   =   $this->request->has('show_salary');
        $this->model->status        =   $this->request->get('status', Career::STATUS_PUBLISH);

        $this->model->contact_no    =   $this->request->get('phone');
        $this->model->email         =   $this->request->get('email');
        $this->model->whatsapp      =   $this->request->get('whatsapp');
        $this->model->website       =   $this->request->get('website');

        if ($this->model->isDirty()) {
            $this->model->save();
        }

        return $this;
    }
}
