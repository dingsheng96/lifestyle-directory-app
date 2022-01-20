@component('mail::message')

<h1 style="text-align: center;">{!! trans('mail.contact_email.title') !!}</h1>
<br>
{!! trans('mail.contact_email.content') !!}

* {{ trans('labels.name') }}: {{ $name }}
* {{ trans('labels.email') }}: {{ $email }}
* {{ trans('labels.contact_no') }}: {{ $phone_no }}
* {{ trans('labels.message') }}: {{ $content }}

<br>

Regards, <br>
{{ config('app.name') }}

@endcomponent