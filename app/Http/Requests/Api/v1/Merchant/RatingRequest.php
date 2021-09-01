<?php

namespace App\Http\Requests\Api\v1\Merchant;

use App\Rules\ExistMerchant;
use Laravel\Passport\Passport;
use App\Http\Requests\Api\v1\BaseRequest;

class RatingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('rating')->setAction('index')->setLog('rating');

        return [
            'merchant_id'   =>  ['required', new ExistMerchant()],
            'scale'         =>  ['required', 'integer', 'in:1,2,3,4,5'],
            'review'        =>  ['nullable', 'string', 'max:255']
        ];
    }
}
