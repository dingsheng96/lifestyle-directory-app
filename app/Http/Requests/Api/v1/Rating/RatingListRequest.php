<?php

namespace App\Http\Requests\Api\v1\Rating;

use App\Rules\ExistMerchant;
use App\Traits\HasPaginationRequest;
use App\Http\Requests\Api\v1\BaseRequest;

class RatingListRequest extends BaseRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('rating')->setAction('index');

        return $this->setPaginationRules([
            'merchant_id'   => ['required', new ExistMerchant()],
        ]);
    }
}
