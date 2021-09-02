<?php

namespace App\Http\Requests\Api\v1\Career;

use App\Models\Career;
use Illuminate\Validation\Rule;
use App\Http\Requests\Api\v1\BaseRequest;

class CareerDetailsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('career')->setAction('read');

        return [
            'career_id' => ['required', Rule::exists(Career::class, 'id')->where('status', Career::STATUS_PUBLISH)->whereNull('deleted_at')]
        ];
    }
}
