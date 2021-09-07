<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::guard(User::USER_TYPE_ADMIN)->check()
            && Gate::any(['application.update']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'application_status' => ['required', 'in:' . User::APPLICATION_STATUS_APPROVED . ',' . User::APPLICATION_STATUS_REJECTED],
            'remarks' => ['required_if:application_status,' . User::APPLICATION_STATUS_REJECTED]
        ];
    }
}
