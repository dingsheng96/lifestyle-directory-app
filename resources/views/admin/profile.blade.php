@extends('admin.layouts.master', ['title' => __('labels.my_profile')])

@section('content')

<div class="container-fluid">


    <div class="row">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.edit', ['module' => __('labels.profile')]) }}</span>
                </div>

                <form action="{{ route('admin.profile.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

                    <div class="card-body">

                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        @if(!empty($user->referral_code))
                        <div class="form-group">
                            <label for="referral_link" class="col-form-label">{{ __('labels.referral_link') }}</label>
                            <span class="form-control-plaintext d-inline">{{ route('merchant.register', ['referral' => $user->referral_code]) }}</span>
                            <button type="button" class="btn btn-purple btn-sm d-inline mr-1" onclick="copyReferralLink('{{ route('merchant.register', ['referral' => $user->referral_code]) }}');">
                                {{ __('labels.copy') }} {{ __('labels.referral_link') }}
                            </button>
                        </div>
                        @endif

                        <hr>

                        <div class="form-group">
                            <label for="password" class="col-form-label">{{ __('labels.new_password') }}</label>
                            <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" autocomplete="off">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small>* <strong>{{ __('messages.password_format') }}</strong></small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">{{ __('labels.new_password_confirmation') }}</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="off">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

@endsection