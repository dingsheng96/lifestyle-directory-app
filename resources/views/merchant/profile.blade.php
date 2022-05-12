@extends('merchant.layouts.master', ['title' => __('labels.my_company_profile')])

@section('content')

<div class="container-fluid">


    <div class="row">

        <div class="col-12 col-md-3">
            <div class="card shadow border">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ $user->logo->full_file_path }}" alt="{{ $user->name . ' logo' }}">
                    </div>

                    <p class="text-muted text-center my-3">{!! $user->active_status_label !!}</p>

                    <ul class="list-group list-group-unbordered my-3">

                        <li class="list-group-item">
                            <b>{{ __('labels.category') }}</b> <span class="float-right">{{ $user->category->name ?? $user->mainBranch->category->name ?? '-' }}</span>
                        </li>

                        <li class="list-group-item">
                            <b>{{ __('labels.ssm_cert') }}</b> <a class="float-right" href="{{ $user->ssm_cert->full_file_path ?? '#' }}" target="_blank"><i class="fas fa-external-link-alt"></i> {{ __('labels.view') }}</a>
                        </li>

                        <li class="list-group-item">
                            <b>{{ __('labels.average_ratings') }}</b> <span class="float-right">{{ $user->rating }}</span>
                        </li>

                        <li class="list-group-item">
                            <b>{{ __('labels.reviews') }}</b> <span class="float-right">{{ $user->ratings_count }}</span>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <div class="card shadow border">

                <form action="{{ route('merchant.profile.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="email" class="col-form-label">{{ __('labels.email') }} <small>({{ trans('labels.username') }})</small></label>
                                    <input id="email" class="form-control" disabled value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white">+</span>
                                        </div>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->mobile_no) }}" class="form-control @error('phone') is-invalid @enderror">
                                    </div>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
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
                            </div>
                            <div class="col-12">
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