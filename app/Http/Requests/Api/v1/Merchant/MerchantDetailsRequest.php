<?php

namespace App\Http\Requests\Api\v1\Merchant;

use App\Rules\ExistMerchant;
use App\Rules\ValidateCoordinates;
use App\Http\Requests\Api\v1\BaseRequest;

class MerchantDetailsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('merchant')->setAction('read')->setLog('merchant');

        return [
            'merchant_id'   => ['required', 'integer', new ExistMerchant()],
            'longitude'     => ['required', new ValidateCoordinates],
            'latitude'      => ['required', new ValidateCoordinates],
        ];
    }
}
