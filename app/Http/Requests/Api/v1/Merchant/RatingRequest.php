<?php

namespace App\Http\Requests\Api\v1\Merchant;

use App\Rules\ExistMerchant;
use Laravel\Passport\Passport;
use App\Http\Requests\Api\v1\BaseRequest;

class RatingRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Passport::hasScope('member');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'merchant_id'   =>  ['required', new ExistMerchant()],
            'scale'         =>  ['required', 'integer', 'in:1,2,3,4,5'],
            'review'        =>  ['nullable', 'string', 'max:255']
        ];
    }
}
