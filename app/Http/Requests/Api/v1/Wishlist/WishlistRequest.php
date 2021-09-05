<?php

namespace App\Http\Requests\Api\v1\Wishlist;

use App\Rules\ValidateCoordinates;
use App\Traits\Requests\HasPagination;
use App\Http\Requests\Api\v1\BaseRequest;

class WishlistRequest extends BaseRequest
{
    use HasPagination;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('wishlist')->setAction('index');

        return $this->setPaginationRules([
            'longitude'     => ['required', new ValidateCoordinates],
            'latitude'      => ['required', new ValidateCoordinates],
        ]);
    }
}
