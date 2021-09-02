<?php

namespace App\Http\Requests\Api\v1\Notification;

use Illuminate\Validation\Rule;
use App\Models\UserNotification;
use App\Http\Requests\Api\v1\BaseRequest;

class ShowNotificationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('notification')->setAction('show');

        return [
            'notification_id' => ['required', Rule::exists(UserNotification::class, 'id')->where('user_id', $this->user()->id)->whereNull('deleted_at')]
        ];
    }
}
