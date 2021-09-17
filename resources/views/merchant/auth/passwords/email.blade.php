@extends('merchant.layouts.master', ['title' => 'Reset Password', 'body' => 'enduser'])

@section('content')

<div class="container container-py">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-body shadow border text-center">

                <h4 class="mb-5">{{ __('Forgot Password') }}</h4>

                <p class="text-muted">
                    {{ __('You may submit your registered email to receive a password reset link.') }}
                </p>

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('merchant.password.email') }}">
                    @csrf
                    <div class="form-group row justify-content-center">
                        <div class="col-md-8">
                            <input id="email" type="email" class="form-control text-center @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('labels.email') }}">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-purple my-3">
                        {{ strtoupper(__('Send Password Reset Link')) }}
                    </button>

                </form>

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