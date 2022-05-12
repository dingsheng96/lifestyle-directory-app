@extends('layouts.master', ['title' => __('modules.register')])

@section('content')

<div id="register" class="my-5">
    <div class="container">
        <h2 class="heading">{!! trans('labels.registration_form') !!}</h2><br><br>
    </div>
    <div class="form-bg">
        <div id="reg-form" class="container">
            <form action="{{ route('merchant.register') }}" method="post" role="form" enctype="multipart/form-data" class="py-5">
                @csrf

                <div class="row mb-5">
                    <div class="col-12 mb-4">
                        <h3>{!! trans('labels.company_details') !!}</h3>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="name" class="label-text required">{!! trans('labels.company_name') !!}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control form-control-lg ucfirst @error('name') is-invalid @enderror" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="contact" class="label-text required">{!! trans('labels.company_contact_no') !!}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone')  }}" class="form-control form-control-lg @error('phone') is-invalid @enderror phone_format" placeholder="Eg: +60xxxxxxxxx" pattern="^(\+60)[0-9][0-9]{8,9}$" required>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="email" class="label-text required">{!! trans('labels.email') !!} ({!! trans('labels.username') !!})</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Eg: example@email.com" required>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="pic_name" class="label-text required">{{ __('labels.pic_full_name_as_nric') }}</label>
                            <div class="form-row" id="name">
                                <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}" class="form-control form-control-lg @error('pic_name') is-invalid @enderror" required>
                                @error('pic_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="password" class="label-text required">{!! trans('labels.password') !!}</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg @error('password') is-invalid @enderror" autocomplete="off" required>
                            <small class="form-text text-muted">* {!! __('messages.password_format') !!}</small>
                            @error('password')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="password_confirmation" class="label-text required ">{!! trans('labels.password_confirmation') !!}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" autocomplete="off" required>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="referral_code" class="label-text">{!! trans('labels.referral_code') !!} ({!! trans('labels.if_any') !!})</label>
                            <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code', $referral) }}" class="form-control form-control-lg @error('referral_code') is-invalid @enderror">
                            @error('referral_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12 mb-4 text-center">
                        <button class="btn btn-attr" type="submit">{{ trans('labels.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@if (Session::has('success'))
<script type="text/javascript">
    $(window).on('load', function () {
        alert("{{ Session::get('success') }}");
    });
</script>
@elseif(Session::has('fail'))
<script type="text/javascript">
    $(window).on('load', function () {
        alert("{{ Session::get('fail') }}");
    });
</script>
@endif
@endpush