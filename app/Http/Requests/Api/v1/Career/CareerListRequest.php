<?php

namespace App\Http\Requests\Api\v1\Career;

use App\Rules\ExistMerchant;
use App\Traits\Requests\HasPagination;
use App\Http\Requests\Api\v1\BaseRequest;

class CareerListRequest extends BaseRequest
{
    use HasPagination;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('career')->setAction('index');

        return $this->setPaginationRules([
            'merchant_id' => ['required', new ExistMerchant()]
        ]);
    }
}
