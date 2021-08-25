@extends('layouts.master', ['title' => trans_choice('modules.member', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-12">

            <div class="card shadow-lg">

                <img src="{{ $member->cover_photo->full_file_path ?? $default_preview }}" alt="cover" class="card-img-top img-fluid" style="max-height: 10rem;">

                <div class="card-body box-profile p-0">

                    <img class="profile-user-img img-fluid img-circle custom-profile-image" src="{{ $member->profile_image->full_file_path ?? $default_preview }}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{ $member->name }}</h3>

                    <p class="text-muted text-center">{!! $member->status_label !!}</p>

                    <div class="list-group list-group-flush" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ __('labels.general') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Profile</a>
                        <a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-controls="messages">Messages</a>
                        <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">Settings</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9 col-12">
            <div class="card shadow-lg">
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
                                    <p class="form-control-plaintext" id="phone">{{ $member->formatted_phone_number }}</p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                                <div class="col-sm-10">
                                    <p class="form-control-plaintext" id="email">{{ $member->email }}</p>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">...</div>
                        <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>
                        <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection