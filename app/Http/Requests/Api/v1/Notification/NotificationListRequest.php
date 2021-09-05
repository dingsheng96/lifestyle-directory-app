<?php

namespace App\Http\Requests\Api\v1\Notification;

use App\Rules\ExistMerchant;
use App\Traits\Requests\HasPagination;
use App\Http\Requests\Api\v1\BaseRequest;

class NotificationListRequest extends BaseRequest
{
    use HasPagination;

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
