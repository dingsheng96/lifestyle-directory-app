<?php

namespace App\Http\Requests\Api\v1\Auth;

use App\Helpers\Response;
use App\Rules\ExistMember;
use App\Rules\ValidateMemberStatus;
use App\Rules\ValidateMemberPassword;
use App\Http\Requests\Api\v1\BaseRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModule('member')->setAction('authenticate')->isAuth();

        return [
            'phone'     =>  ['required', new ExistMember('mobile_no'), new ValidateMemberStatus('mobile_no')],
            'password'  =>  ['required', new ValidateMemberPassword('mobile_no', $this->get('phone'))]
        ];
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
            ->withStatusCode('modules.member', 'actions.authenticate.validation')
            ->withMessage($error->first())
            ->getResponse();

        activity()->useLog('api:auth')
            ->withProperties($response)
            ->log($error->first());

        throw new HttpResponseException(
            response()->json($response, 422)
        );

        parent::failedValidation($validator);
    }
}
