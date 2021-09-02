<?php

namespace App\Http\Requests\Api\v1\Wishlist;

use App\Rules\ValidateCoordinates;
use App\Rules\ExistWishlistMerchant;
use App\Traits\HasPaginationRequest;
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
}
