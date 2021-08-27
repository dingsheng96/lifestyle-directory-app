<?php

namespace App\Helpers;

class Message
{
    public static function instance()
    {
        return new self();
    }

    public function format(string $action, string $module, string $status = 'fail'): string
    {
        return __('messages.' . $action . '.' . $status, ['module' => $module]);
    }

    public function logout($status = 'success')
    {
        return __('messages.logout_' . $status);
    }

    public function login($status = 'success')
    {
        return __('messages.login_' . $status);
    }

    public function register($status = 'success')
    {
        return __('messages.register_' . $status);
    }
}
