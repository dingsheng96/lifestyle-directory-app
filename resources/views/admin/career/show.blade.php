@extends('admin.layouts.master', ['title' => trans_choice('modules.career', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.career', 1)]) }}</span>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="position" class="col-form-label col-sm-2">{{ __('labels.job_title') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="position">{{ $career->position }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="company" class="col-form-label col-sm-2">{{ __('labels.company') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="company">{{ $career->branch->name }}</span>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="location" class="col-form-label col-sm-2">{{ __('labels.location') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="location">{{ $career->branch->address->location_city_state }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="status">{!! $career->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="salary" class="col-form-label col-sm-2">{{ __('labels.salary') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="salary">{{ $career->salary_range }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-sm-2">{{ __('labels.description') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext">{!! $career->description ?? '-' !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="about" class="col-form-label col-sm-2">{{ __('labels.about') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext">{!! $career->about ?? '-' !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="benefit" class="col-form-label col-sm-2">{{ __('labels.benefits') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext">{!! $career->benefit ?? '-' !!}</span>
                        </div>
                    </div>

                    <hr>
                    <p class="h5 mb-4">{{ __('labels.applicant_can_apply_via') }}</p>

                    <div class="form-group row">
                        <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="phone">{{ $career->formatted_mobile_no ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="email">{{ $career->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="whatsapp" class="col-form-label col-sm-2">{{ __('labels.whatsapp') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="whatsapp">{{ $career->formatted_whatsapp ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="website" class="col-form-label col-sm-2">{{ __('labels.website') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="website">{{ $career->website ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.careers.index') }}" class="btn btn-default">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection