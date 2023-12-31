<?php

namespace App\Http\Requests\Api\v1\Rating;

use App\Rules\ExistMerchant;
use Illuminate\Support\Facades\Auth;
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
        $this->setModule('rating')->setAction('create');

        return [
            'merchant_id'   =>  ['required', new ExistMerchant()],
            'scale'         =>  ['required', 'integer', 'in:1,2,3,4,5'],
            'review'        =>  ['nullable', 'string', 'max:255']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }
}
