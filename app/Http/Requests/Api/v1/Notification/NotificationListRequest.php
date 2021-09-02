<?php

namespace App\Http\Requests\Api\v1\Notification;

use App\Rules\ExistMerchant;
use App\Traits\HasPaginationRequest;
use App\Http\Requests\Api\v1\BaseRequest;

class NotificationListRequest extends BaseRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('notification')->setAction('index');

        return $this->setPaginationRules();
    }
}
