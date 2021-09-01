@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">

        <div class="col-12 col-md-3">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.merchant', 1)]) }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-general-list" data-toggle="list" href="#list-general" role="tab" aria-controls="general">{{ __('labels.general') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-location-list" data-toggle="list" href="#list-location" role="tab" aria-controls="location">{{ __('labels.location') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-controls="settings">{{ __('labels.settings') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-gallery-list" data-toggle="list" href="#list-gallery" role="tab" aria-controls="gallery">{{ __('labels.gallery') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9">
            <div class="card shadow-lg">

                <form action="{{ route('merchants.store') }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="tab-content" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="list-general" role="tabpanel" aria-labelledby="list-general-list">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">{{ __('labels.company_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                            @error('name')
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
                                            <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">+</span>
                                                </div>
                                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                            </div>
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
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
                                            <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no') }}" class="form-control @error('reg_no') is-invalid @enderror">
                                            @error('reg_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="file" class="col-form-label">{{ __('labels.ssm_cert') }} <span class="text-danger">*</span></label>
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

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="category" class="col-form-label">{{ __('labels.category') }} <span class="text-danger">*</span></label>
                                            <select name="category" id="category" class="form-control select2 @error('category') is-invalid @enderror">
                                                <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.category'))]) }} ---</option>
                                                @forelse ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : null }}>{{ $category->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                            @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                                @foreach ($active_statuses as $status => $display)
                                                <option value="{{ $status }}" {{ old('status', 'active') == $status ? 'selected' : null }}>{{ $display }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
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
                                            <label for="logo" class="col-form-label">{{ __('labels.logo') }} <span class="text-danger">*</span></label>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                    @error('logo')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="whatsapp" class="col-form-label">{{ __('labels.whatsapp') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">+</span>
                                                </div>
                                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" class="form-control @error('whatsapp') is-invalid @enderror">
                                            </div>
                                            @error('whatsapp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                            <input type="url" name="website" id="website" value="{{ old('website') }}" class="form-control @error('website') is-invalid @enderror">
                                            @error('website')
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
                                            <label for="facebook" class="col-form-label">{{ __('labels.facebook') }}</label>
                                            <input type="url" name="facebook" id="facebook" value="{{ old('facebook') }}" class="form-control @error('facebook') is-invalid @enderror">
                                            @error('facebook')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="instagram" class="col-form-label">{{ __('labels.instagram') }}</label>
                                            <input type="url" name="instagram" id="instagram" value="{{ old('instagram') }}" class="form-control @error('instagram') is-invalid @enderror">
                                            @error('instagram')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="list-location" role="tabpanel" aria-labelledby="list-location-list">

                                <div class="form-group">
                                    <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1') }}">
                                    @error('address_1')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address_2" class="col-form-label">{{ __('labels.address_2') }}</label>
                                    <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2') }}">
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
                                            <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}">
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
                                                @forelse ($countryStates as $state)
                                                <option value="{{ $state->id }}" {{ old('country_state') == $state->id ? 'selected' : null }}>{{ $state->name }}</option>
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
                                            <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', 0) }}" data-city-route="{{ route('data.country-states.cities', ['__REPLACE__']) }}">
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
                                            <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', 0) }}">
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
                                            <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', 0) }}">
                                            @error('longitude')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-purple">
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ __('labels.get_coordinate') }}
                                    </button>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">

                                <div class="form-group">
                                    <label for="tbl_oprating_hour" class="col-form-label">{{ __('labels.operating_hour') }}</label>
                                    @include('components.operating_table')
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}" class="form-control @error('pic_name') is-invalid @enderror">
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
                                                <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone')  }}" class="form-control @error('pic_phone') is-invalid @enderror">
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
                                            <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email') }}" class="form-control @error('pic_email') is-invalid @enderror">
                                            @error('pic_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="col-form-label">{{ __('labels.password') }} <span class="text-danger">*</span></label>
                                            <input type="password" name="password" id="password" value="{{ old('password')  }}" class="form-control @error('password') is-invalid @enderror" autocomplete="off">
                                            <small>{!! __('messages.password_format') !!}</small>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-form-label">{{ __('labels.password_confirmation') }} <span class="text-danger">*</span></label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="off">
                                            @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="list-gallery" role="tabpanel" aria-labelledby="list-gallery-list">

                                <div class="form-group">
                                    <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                                    <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png" data-action="update">
                                        <div class="dz-default dz-message">
                                            <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                            <h4>{{ __('messages.drag_and_drop') }}</h4>
                                            <ul class="list-unstyled">{!! trans_choice('messages.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files]) !!}</ul>
                                        </div>
                                    </div>
                                    @error('files')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('merchants.index') }}" role="button" class="btn btn-light mx-2">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
                        </a>
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