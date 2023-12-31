@extends('merchant.layouts.master', ['title' => __('modules.login'), 'body' => 'login-page'])

@section('content')
<div class="login-box">

    <div class="login-logo mb-5">
        <img src="{{ asset('assets/logo.png') }}" class="d-block mx-auto w-50">
    </div>

    @if(session('success'))
    <div class="alert alert-success mb-3" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if(session('verified'))
    <div class="alert alert-info mb-3" role="alert">
        {{ trans('app.verified') }}
    </div>
    @endif

    <div class="card card-body login-card-body shadow">

        <p class="login-box-msg">{{ __('app.login_title_main') }}</p>

        <form action="{{ route('merchant.login') }}" method="post" role="form" enctype="multipart/form-data">
            @csrf

            <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('labels.email') }}" name="email" value="{{ old('email') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('labels.password') }}" autocomplete="on">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="icheck-purple">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : null }}>
                        <label for="remember">{{ __('labels.remember_me') }}</label>
                    </div>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-purple btn-block">{{ __('labels.login') }}</button>
                </div>
            </div>

        </form>

        <a href="{{ route('merchant.password.request') }}" class="mb-1">{{ __('app.login_btn_forgot_password') }}</a>
        <a href="{{ route('merchant.register') }}" class="mb-1">{{ __('app.login_btn_register_merchant') }}</a>

    </div>
</div>


@endsection