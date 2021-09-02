<?php

namespace App\Http\Requests\Api\v1;

use App\Rules\ValidateCoordinates;
use App\Traits\HasPaginationRequest;
use App\Http\Requests\Api\v1\BaseRequest;

class HomeRequest extends BaseRequest
{
    use HasPaginationRequest;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('dashboard')->setAction('index');

        return $this->setPaginationRules([
            'longitude'     => ['required', new ValidateCoordinates],
            'latitude'      => ['required', new ValidateCoordinates]
        ]);
    }
}
