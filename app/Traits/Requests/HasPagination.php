<?php

namespace App\Traits\Requests;

trait HasPagination
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->setPaginationRules();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return $this->setPaginationMessages();
    }

    protected function setPaginationRules(array $rules = []): array
    {
        $pagination_rules = ['page' => ['required', 'integer', 'regex:/^[1-9]\d*$/']];

        if (!empty($rules) && count($rules) > 0) {
            $pagination_rules = array_merge($pagination_rules, $rules);
        }

        return $pagination_rules;
    }

    protected function setPaginationMessages(array $messages = []): array
    {
        $pagination_messages = ['page.regex' => __('validation.custom.pagination')];

        if (!empty($messages) && count($messages) > 0) {
            $pagination_messages = array_merge($pagination_messages, $messages);
        }

        return $pagination_messages;
    }
}
