@extends('admin.layouts.master', ['title' => trans_choice('modules.application', 2)])

@section('content')

<div class="container-fluid">

    @if(session('resent'))
    <div class="alert alert-info mb-3" role="alert">
        {{ trans('app.resend_verify') }}
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-body">

                    <h5 class="mb-4">{{ __('labels.pic_details') }}</h5>

                    <div class="form-group row">
                        <label for="pic_name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="pic_name">{{ $application->branchDetail->pic_name ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pic_phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="pic_phone">{{ $application->branchDetail->formatted_pic_contact?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="pic_email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="pic_email">{{ $application->branchDetail->pic_email ?? null }}</span>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-4">{{ __('labels.company_details') }}</h5>

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.company_name') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="name">{{ $application->name ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="phone">{{ $application->formatted_mobile_no ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="email">{{ $application->email ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="reg_no" class="col-form-label col-sm-2">{{ __('labels.reg_no') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="reg_no">{{ $application->branchDetail->reg_no ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-form-label col-sm-2">{{ __('labels.address') }}</label>
                        <div class="col-sm-10">
                            <span id="address" class="form-control-plaintext">{{ $application->address->full_address ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="media" class="col-form-label col-sm-2">{{ trans_choice('labels.document', 2) }}</label>
                        <div class="col-sm-10">
                            @include('admin.components.tbl_image', ['images' => $application->media])
                        </div>
                    </div>

                    @if ($application->referrals->first())

                    <hr>

                    <h5 class="mb-4">{{ __('labels.referral_details') }}</h5>

                    <div class="form-group row">
                        <label for="referral_name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="referral_name">{{ $application->referrals->first()->name ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="referral_email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="referral_email">{{ $application->referrals->first()->email ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="referral_code" class="col-form-label col-sm-2">{{ __('labels.referral_code') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="referral_code">{{ $application->referrals->first()->referral_code ?? null }}</span>
                        </div>
                    </div>

                    @endif
                </div>

                <div class="card-footer bg-transparent text-center text-md-right">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-default ml-2">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                    @if (!$application->has_filled_branch_details)
                    <button type="submit" class="btn btn-purple float-right ml-2" form="resendVerificationEmailForm">
                        <i class="fas fa-envelope"></i>
                        {{ __('app.btn_resend_verification_email') }}
                    </button>
                    <form method="POST" action="{{ route('merchant.verification.resend', ['user' => $application->id]) }}" id="resendVerificationEmailForm">
                        @csrf
                    </form>
                    @endif
                </div>


            </div>
        </div>
    </div>
</div>



@endsection