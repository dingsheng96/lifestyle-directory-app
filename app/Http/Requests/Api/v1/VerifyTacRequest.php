<?php

namespace App\Http\Requests\Api\v1;

use App\Models\TacNumber;
use App\Http\Requests\Api\v1\BaseRequest;

class VerifyTacRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('tac')->setAction('authenticate');

        return [
            'phone'     =>  ['required'],
            'tac'       =>  ['required'],
            'purpose'   =>  ['required', 'in:' . TacNumber::PURPOSE_RESET_PASSWORD],
        ];
    }
}
