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
                            <label for="reg_no" class="label-text required">{!! trans('labels.reg_no') !!}</label>
                            <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no') }}" class="form-control form-control-lg @error('reg_no') is-invalid @enderror">
                            @error('reg_no')
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
                            <label for="category" class='label-text required'>{!! trans('labels.category') !!}</label>
                            <select name="category" id="category" class="form-control form-control-lg @error('category') is-invalid @enderror" required>
                                <option value="0" selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.category'))]) }} ---</option>
                                @forelse ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category')==$category->id ? 'selected' : null }}>{{ $category->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('category')
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
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="address_1" class="label-text required">{{ __('labels.address_1') }}</label>
                            <input type="text" name="address_1" id="address_1" class="form-control form-control-lg @error('address_1') is-invalid @enderror" value="{{ old('address_1') }}" required>
                            @error('address_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="address_2" class="label-text">{{ __('labels.address_2') }}</label>
                            <input type="text" name="address_2" id="address_2" class="form-control form-control-lg @error('address_2') is-invalid @enderror" value="{{ old('address_2') }}">
                            @error('address_2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="postcode" class="label-text required">{{ __('labels.postcode') }}</label>
                            <input type="text" name="postcode" id="postcode" class="form-control form-control-lg @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}" required>
                            @error('postcode')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="country_state" class="label-text required">{{ trans_choice('labels.country_state', 1) }}</label>
                            <select name="country_state" id="country_state" class="form-control form-control-lg @error('country_state') is-invalid @enderror city-filter" required>
                                <option value="0" selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country_state', 1))]) }} ---</option>
                                @forelse ($country_states as $state)
                                <option value="{{ $state->id }}" {{ old('country_state')==$state->id ? 'selected' : null }}>{{ $state->name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('country_state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="city" class="label-text required">{{ trans_choice('labels.city', 1) }}</label>
                            <select name="city" id="city" class="form-control form-control-lg @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', 0) }}" data-city-route="{{ route('merchant.data.country-states.cities', ['__REPLACE__']) }}" required>
                                <option value="0" selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.city', 1))]) }} ---</option>
                            </select>
                            @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12 mb-4">
                        <h3>{!! trans('labels.personal_details') !!}</h3>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="form-group-lg">
                            <label for="pic_name" class="label-text required">{{ __('labels.full_name_as_nric') }}</label>
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
                            <label for="pic_phone" class="label-text required">{!! trans('labels.contact_no') !!}</label>
                            <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone')  }}" class="form-control form-control-lg @error('pic_phone') is-invalid @enderror phone_format" placeholder="Eg: +6012xxxxxxx" pattern="^(\+601)[0-46-9][0-9]{7,8}$" required>
                            @error('pic_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="pic_email" class="label-text required">{!! trans('labels.email') !!}</label>
                            <input type="email" value="{{ old('pic_email') }}" class="form-control form-control-lg @error('pic_email') is-invalid @enderror" id="pic_email" name="pic_email" placeholder="Eg: example@email.com" required>
                            @error('pic_email')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12 mb-4">
                        <h3>{!! trans('labels.upload_verification_documents') !!}</h3>
                    </div>

                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="logo" class="label-text required">{{ __('labels.shop_image') }}</label>
                            <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png" required>
                            @error('logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small id="passwordHelpBlock" class="form-text text-muted">
                                <strong>{!! trans_choice('messages.upload_file_rules_text', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</strong>
                            </small>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mb-4">
                        <div class="form-group-lg">
                            <label for="file" class="label-text required">{{ __('labels.ssm_cert') }}</label>
                            <input type="file" name="ssm_cert" id="ssm_cert" value="{{ old('ssm_cert') }}" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf" required>
                            @error('ssm_cert')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small id="passwordHelpBlock" class="form-text text-muted">
                                <strong>{!! trans_choice('messages.upload_file_rules_text', 1, ['maxsize' => '2mb', 'extensions' => 'PDF']) !!}</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12 mb-4">
                        <div class="form-group-lg">
                            <input type="checkbox" name="agreement" id="agreement" required {{ old('agreement') ? 'checked' : null }}>
                            <label for="agreement" class="mx-2">{!! trans('labels.register_agreement') !!}</label>
                        </div>
                    </div>
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