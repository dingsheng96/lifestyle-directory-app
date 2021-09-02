@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">

        <div class="col-12 col-md-3">
            <div class="card shadow">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.merchant', 1)]) }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ __('labels.general') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-gallery-list" data-toggle="list" href="#list-gallery" role="tab" aria-controls="gallery">{{ __('labels.gallery') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-branches-list" data-toggle="list" href="#list-branches" role="tab" aria-controls="branches">{{ trans_choice('labels.branch', 2) }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <div class="card shadow">

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
                                    <span class="form-control-plaintext" id="phone">{{ $merchant->formatted_phone_number }}</span>
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
                                    <p class="form-control-plaintext" id="category">{!! $merchant->category->name !!}</p>
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

                            <div class="form-group row">
                                <label for="whatsapp" class="col-form-label col-sm-2">{{ __('labels.whatsapp') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="whatsapp">
                                        @if (!empty($merchant->branchDetail->whatsapp))
                                        <a href="https://wa.me/{{ $merchant->branchDetail->whatsapp }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->branchDetail->formatted_whatsapp }}</a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="website" class="col-form-label col-sm-2">{{ __('labels.website') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="ssm_cert">
                                        @if (!empty($merchant->branchDetail->website))
                                        <a href="{{ $merchant->branchDetail->website }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->branchDetail->website }}</a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="facebook" class="col-form-label col-sm-2">{{ __('labels.facebook') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="facebook">
                                        @if (!empty($merchant->branchDetail->facebook))
                                        <a href="{{ $merchant->branchDetail->facebook }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->branchDetail->facebook }}</a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="instagram" class="col-form-label col-sm-2">{{ __('labels.instagram') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="instagram">
                                        @if (!empty($merchant->branchDetail->instagram))
                                        <a href="{{ $merchant->branchDetail->instagram }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $merchant->branchDetail->instagram }}</a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>

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
                                        {{ $merchant->branchDetail->formatted_pic_phone }}
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

                        </div>

                        <div class="tab-pane fade" id="list-gallery" role="tabpanel" aria-labelledby="list-gallery-list">

                            <div class="form-group">
                                @include('components.image_table', ['images' => $image_and_thumbnail, 'thumbnail' => false, 'action' => false])
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

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('merchants.index') }}" role="button" class="btn btn-light">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush