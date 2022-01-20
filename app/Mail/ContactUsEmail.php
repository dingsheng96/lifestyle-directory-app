<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = $this->data['first_name'] . ' ' . $this->data['last_name'];
        $email = $this->data['email'];
        $phone_no = $this->data['phone_no'];
        $content = array_key_exists('message', $this->data)
            ? $this->data['message']
            : "-";


        return $this->from(config('mail.from.address'))
            ->subject(trans('mail.contact_email.subject'))
            ->markdown('mail.contact_us', compact('name', 'email', 'phone_no', 'content'));
    }
}
