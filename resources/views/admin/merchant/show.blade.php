@extends('admin.layouts.master', ['title' => trans_choice('modules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">

        <div class="col-12 col-md-3">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.merchant', 1)]) }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush toggleTab" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ __('labels.general') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-gallery-list" data-toggle="list" href="#list-gallery" role="tab" aria-controls="gallery">{{ __('labels.gallery') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-branches-list" data-toggle="list" href="#list-branches" role="tab" aria-controls="branches">{{ trans_choice('labels.branch', 2) }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <div class="card shadow border">

                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">

                            <div class="form-group row">
                                <label for="name" class="col-form-label col-sm-2">{{ __('labels.company_name') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="name">{{ $merchant->name }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="phone">{{ $merchant->formatted_mobile_no }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="email">{{ $merchant->email }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reg_no" class="col-form-label col-sm-2">{{ __('labels.reg_no') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="reg_no">{{ $merchant->branchDetail->reg_no ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="email">{{ $merchant->email }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category" class="col-form-label col-sm-2">{{ __('labels.category') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="category">{!! $merchant->category->name ?? '-' !!}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="status">{!! $merchant->status_label !!}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="ssm_cert" class="col-form-label col-sm-2">{{ __('labels.ssm_cert') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="ssm_cert">
                                        <a href="{{ $merchant->ssm_cert->full_file_path ?? null }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->ssm_cert->original_filename ?? '-' }}</a>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="logo" class="col-form-label col-sm-2">{{ __('labels.logo') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="ssm_cert">
                                        <a href="{{ $merchant->logo->full_file_path ?? null }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->logo->original_filename ?? '-' }}</a>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-form-label col-sm-2">{{ __('labels.address') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="address">
                                        {{ $merchant->address->full_address }}
                                    </span>
                                </div>
                            </div>

                            <hr>

                            @foreach ($social_media as $media_key => $media_text)
                            <div class="form-group row">
                                <label for="{{ $media_text }}" class="col-form-label col-sm-2">{{ $media_text }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext">
                                        {{ $merchant->userSocialMedia->where('media_key', $media_key)->first()->media_value ?? '-' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach

                            <hr>

                            <div class="form-group row">
                                <label for="pic_name" class="col-form-label col-sm-2">{{ __('labels.pic_name') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="pic_name">
                                        {{ $merchant->branchDetail->pic_name }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pic_phone" class="col-form-label col-sm-2">{{ __('labels.pic_contact') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="pic_phone">
                                        {{ $merchant->branchDetail->formatted_pic_contact }}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="pic_email" class="col-form-label col-sm-2">{{ __('labels.pic_email') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="pic_email">
                                        {{ $merchant->branchDetail->pic_email ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <hr>

                            <div class="form-group row">
                                <label for="description" class="col-form-label col-sm-2">{{ __('labels.description') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="description">
                                        {!! nl2br($merchant->branchDetail->description) ?? '-' !!}
                                    </span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="services" class="col-form-label col-sm-2">{{ __('labels.services') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="services">
                                        {!! nl2br($merchant->branchDetail->services) ?? '-' !!}
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="career_desc" class="col-form-label col-sm-2">{{ __('labels.career_introduction') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="career_desc">
                                        {!! nl2br($merchant->branchDetail->career_description) ?? '-' !!}
                                    </span>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="list-gallery" role="tabpanel" aria-labelledby="list-gallery-list">

                            <div class="form-group">
                                @include('admin.components.tbl_image', ['images' => $image_and_thumbnail, 'thumbnail' => false, 'action' => false])
                            </div>

                        </div>

                        <div class="tab-pane fade" id="list-branches" role="tabpanel" aria-labelledby="list-branches-list">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        {!! $dataTable->table() !!}
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <button type="button" class="btn btn-default btnPreviousTab">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.previous') }}
                    </button>
                    <button type="button" class="btn btn-purple btnNextTab float-right">
                        <i class="fas fa-caret-right"></i>
                        {{ __('labels.next') }}
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush