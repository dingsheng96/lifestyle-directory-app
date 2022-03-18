@extends('merchant.layouts.master', ['title' => __('labels.my_profile')])

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

                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">{{ __('labels.general') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab">{{ __('labels.details') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#social" data-toggle="tab">{{ __('labels.social_media') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#location" data-toggle="tab">{{ __('labels.location') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">{{ __('labels.settings') }}</a></li>
                        </ul>

                        <div class="tab-content my-3">
                            <div class="active tab-pane" id="general">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
                                            <span id="email" class="form-control-plaintext">{{ $user->email }}</span>
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

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no', $user->branchDetail->reg_no) }}" class="form-control @error('reg_no') is-invalid @enderror">
                                            @error('reg_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="listing_status" class="col-form-label">{{ __('labels.listing_status') }} <span class="text-danger">*</span></label>
                                            <select name="listing_status" id="listing_status" class="form-control select2 @error('listing_status') is-invalid @enderror">
                                                @forelse ($publish_statuses as $status => $display)
                                                <option value="{{ $status }}" {{ old('listing_status', $user->listing_status) == $status ? 'selected' : null }}>{{ $display }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('listing_status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="logo" class="col-form-label">{{ __('labels.change_logo') }}</label>
                                            <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="file" class="col-form-label">{{ __('labels.change_ssm_cert') }}</label>
                                            <input type="file" name="ssm_cert" id="ssm_cert" value="{{ old('ssm_cert') }}" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf">
                                            @error('ssm_cert')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'PDF']) !!}</ul>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane" id="details">
                                <div class="form-group">
                                    <label for="desription" class="col-form-label">{{ __('labels.description') }}</label>
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $user->branchDetail->description) }}</textarea>
                                    @error('desription')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="services" class="col-form-label">{{ __('labels.services') }}</label>
                                    <textarea name="services" id="services" cols="30" rows="5" class="form-control @error('service') is-invalid @enderror">{{ old('services', $user->branchDetail->services) }}</textarea>
                                    @error('services')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="career_desc" class="col-form-label">{{ __('labels.career_introduction') }}</label>
                                    <textarea name="career_desc" id="career_desc" cols="30" rows="5" class="form-control @error('career_desc') is-invalid @enderror">{{ old('career_desc', $user->branchDetail->career_description) }}</textarea>
                                    @error('career_desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tbl_oprating_hour" class="col-form-label">{{ __('labels.operating_hour') }}</label>
                                    @include('components.tbl_operation', ['operation_hours' => $user->operationHours])
                                </div>
                            </div>

                            <div class="tab-pane" id="social">
                                @foreach ($social_media as $media_key => $media_text)
                                <div class="form-group row">
                                    <label for="{{ $media_text }}" class="col-form-label col-sm-2">{{ $media_text }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="{{ $media_key }}" id="{{ $media_text }}" value="{{ old($media_key, optional(optional(optional($user->userSocialMedia)->where('media_key', $media_key))->first())->media_value) }}"
                                            class="form-control @error($media_key) is-invalid @enderror">
                                        @error($media_key)
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="tab-pane" id="location">
                                <div id="location-address-panel" data-route="{{ route('merchant.data.geocoding') }}">
                                    <div class="form-group">
                                        <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $user->address->address_1) }}">
                                        @error('address_1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="address_2" class="col-form-label">{{ __('labels.address_2') }}</label>
                                        <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $user->address->address_2) }}">
                                        @error('address_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $user->address->postcode) }}">
                                                @error('postcode')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-danger">*</span></label>
                                                <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror city-filter">
                                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country_state', 1))]) }} ---</option>
                                                    @forelse ($country_states as $state)
                                                    <option value="{{ $state->id }}" {{ old('country_state', $user->address->countryState->id) == $state->id ? 'selected' : null }}>{{ $state->name }}</option>
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
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-danger">*</span></label>
                                                <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $user->address->city_id) }}"
                                                    data-city-route="{{ route('merchant.data.country-states.cities', ['__REPLACE__']) }}">
                                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.city', 1))]) }} ---</option>
                                                </select>
                                                @error('city')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="latitude" class="col-form-label">{{ __('labels.latitude') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $user->address->latitude ?? 0) }}">
                                                @error('latitude')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="longitude" class="col-form-label">{{ __('labels.longitude') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $user->address->longitude ?? 0) }}">
                                                @error('longitude')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="button" class="btn btn-purple" id="btn_get_coordinates">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ __('labels.get_coordinate') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="settings">

                                <div class="form-group">
                                    <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $user->branchDetail->pic_name) }}" class="form-control @error('pic_name') is-invalid @enderror">
                                    @error('pic_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">+</span>
                                                </div>
                                                <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $user->branchDetail->pic_contact)  }}" class="form-control @error('pic_phone') is-invalid @enderror">
                                            </div>
                                            @error('pic_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }} <span class="text-danger">*</span></label>
                                            <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email', $user->branchDetail->pic_email) }}" class="form-control @error('pic_email') is-invalid @enderror">
                                            @error('pic_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

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