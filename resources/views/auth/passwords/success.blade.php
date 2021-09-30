@extends('layouts.master', ['title' => 'Reset Password', 'body' => 'enduser'])

@section('content')

<div class="container container-py">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border text-center py-3 border">

                <div class="card-body text-center">
                    <img src="{{ asset('assets/check.png') }}" alt="success" class="status-img card-img-top">
                    <h4 class="my-5 text-muted">{{ __('passwords.reset') }}</h4>
                    <a role="button" class="btn btn-purple" href="{{ url('/') }}">{{ __('Visit Our Website') }}</a>
                </div>

            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <p class="text-muted text-center">{!! __('labels.copyright') !!}</p>
        </div>
    </div>
</div>

@endsection