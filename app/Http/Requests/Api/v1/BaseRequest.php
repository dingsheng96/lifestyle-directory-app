<?php

namespace App\Http\Requests\Api\v1;

use App\Helpers\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    protected $module, $action, $log = '';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard('api')->check();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $error = collect((new ValidationException($validator))->errors())
            ->flatMap(function ($values) {
                return $values;
            });

        $response = Response::instance()
            ->withStatus('fail')
            ->withStatusCode('modules.' . $this->module, 'actions.' . $this->action . '.validation')
            ->withMessage($error->first())
            ->getResponse();

        throw new HttpResponseException(
            response()->json($response, 422)
        );

        parent::failedValidation($validator);
    }

    protected function setModule(string $module)
    {
        $this->module = $module;

        return $this;
    }

    protected function setAction(string $action)
    {
        $this->action = $action;

        return $this;
    }
}
