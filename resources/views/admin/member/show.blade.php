@extends('admin.layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-12">

            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.member', 1)]) }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ __('labels.general') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-12">
            <div class="card shadow border">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">

                            <div class="form-group row">
                                <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="name">{{ $member->name }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-form-label col-sm-2">{{ __('labels.contact_no') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="phone">{{ $member->formatted_mobile_no }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="email">{{ $member->email }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="status">{!! $member->active_status_label !!}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="profile_image" class="col-form-label col-sm-2">{{ __('labels.profile_image') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="profile_image">
                                        <a href="{{ $member->profile_image->full_file_path ?? null }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $member->profile_image->original_filename ?? '-' }}</a>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cover_photo" class="col-form-label col-sm-2">{{ __('labels.cover_photo') }}</label>
                                <div class="col-sm-10">
                                    <span class="form-control-plaintext" id="cover_photo">
                                        <a href="{{ $member->cover_photo->full_file_path ?? null }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $member->cover_photo->original_filename ?? '-' }}</a>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.members.index') }}" role="button" class="btn btn-default">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection