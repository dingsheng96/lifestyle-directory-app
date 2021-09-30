@extends('admin.layouts.master', ['title' => trans_choice('modules.application', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow border">
                <form action="{{ route('admin.applications.update', ['application' => $application->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <input type="hidden" name="application_status" value="{{ $status_approve }}">

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
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i>
                            {{ __('labels.approved') }}
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                            <i class="fas fa-times-circle"></i>
                            {{ __('labels.rejected') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade hide" tabindex="-1" role="dialog" id="rejectModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('labels.state_reason') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.applications.update', ['application' => $application->id]) }}" method="post" role="form" enctype="multipart/form-data">
                @csrf
                @method('put')
                <input type="hidden" name="application_status" value="{{ $status_reject }}">
                <div class="modal-body">
                    <div class="form-group">
                        <textarea name="remarks" id="remarks" cols="30" rows="5" class="form-control @error('remarks') is-invalid @enderror" placeholder="{{ __('labels.state_reason') }}">{{ old('remarks') }}</textarea>
                        @error('remarks')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-purple">
                        <i class="fas fa-paper-plane"></i>
                        {{ __('labels.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@error('remarks')

@push('scripts')

<script type="text/javascript">
    $(window).on('load', function() {
        $('#rejectModal').modal('show');
    });
</script>

@endpush

@enderror