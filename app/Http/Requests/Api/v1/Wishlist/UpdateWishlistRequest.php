<?php

namespace App\Http\Requests\Api\v1\Wishlist;

use App\Rules\ExistWishlistMerchant;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\BaseRequest;

class UpdateWishlistRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('wishlist')->setAction('update');

        return [
            'merchant_id'   =>  ['required', new ExistWishlistMerchant($this->user())],
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
