<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SendContactUsEmailRequest;

class ContactController extends Controller
{
    public function sendEmail(SendContactUsEmailRequest $request)
    {
        try {

            Mail::to(config('mail.support_mail'))->send(
                new ContactUsEmail($request->only([
                    'first_name', 'last_name', 'email',
                    'phone_no', 'message'
                ]))
            );

            return redirect()->route('home', '#contact')
                ->with('send_mail_success', trans('messages.send_mail_success'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect()->route('home', '#contact')
                ->withInput()
                ->with('send_mail_fail', trans('messages.send_mail_fail'));
        }
    }
}
