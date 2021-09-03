@extends('admin.layouts.master', ['title' => __('modules.login'), 'guest_view' => true, 'body' => 'login-page'])

@section('content')
<div class="login-box">

    <div class="login-logo">
        <img src="{{ asset('storage/logo.png') }}" class="d-block mx-auto w-50">
    </div>

    <div class="card card-body login-card-body shadow-lg my-5">

        <p class="login-box-msg">{{ __('app.login_title_main') }}</p>

        <form action="{{ route('admin.login') }}" method="post" role="form" enctype="multipart/form-data">
            @csrf

            <div class="input-group mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('labels.email') }}" name="email">
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

        <p class="mb-1">
            <a href="{{ route('admin.password.request') }}">{{ __('app.login_btn_forgot_password') }}</a>
        </p>

    </div>
</div>


@endsection