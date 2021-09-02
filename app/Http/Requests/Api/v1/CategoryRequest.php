<?php

namespace App\Http\Requests\Api\v1;

use App\Traits\HasPaginationRequest;
use App\Http\Requests\Api\v1\BaseRequest;

class CategoryRequest extends BaseRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('category')->setAction('index');

        return $this->setPaginationRules();
    }
}
