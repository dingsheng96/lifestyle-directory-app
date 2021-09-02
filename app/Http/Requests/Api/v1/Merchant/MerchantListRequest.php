<?php

namespace App\Http\Requests\Api\v1\Merchant;

use App\Models\Category;
use App\Rules\ValidateCoordinates;
use App\Traits\HasPaginationRequest;
use App\Http\Requests\Api\v1\BaseRequest;

class MerchantListRequest extends BaseRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('merchant')->setAction('index');

        return $this->setPaginationRules([
            'longitude'     => ['required', new ValidateCoordinates],
            'latitude'      => ['required', new ValidateCoordinates],
            'category_id'   => ['required', 'exists:' . Category::class . ',id']
        ]);
    }
}
