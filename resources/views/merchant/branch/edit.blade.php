@extends('merchant.layouts.master', ['title' => trans_choice('modules.company', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.edit', ['module' => trans_choice('modules.company', 1)]) }}</span>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush toggleTab" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-controls="home">{{ __('labels.general') }}</a>
                        <a class="list-group-item list-group-item-action" id="list-review-list" data-toggle="list" href="#list-reviews" role="tab" aria-controls="reviews">{{ trans_choice('labels.review', 2) }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.edit', ['module' => trans_choice('modules.company', 1)]) }}</span>
                </div>
                <form action="{{ route('merchant.branches.update', ['branch' => $branch->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="name" class="col-form-label">{{ __('labels.branch_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name" value="{{ old('name', $branch->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no', $branch->branchDetail->reg_no ?? null) }}" class="form-control @error('reg_no') is-invalid @enderror">
                                            @error('reg_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">+</span>
                                                </div>
                                                <input type="text" name="phone" id="phone" value="{{ old('phone', $branch->mobile_no) }}" class="form-control @error('phone') is-invalid @enderror">
                                            </div>
                                            @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-danger">*</span> <small>({{ trans('labels.username') }})</small></label>
                                            <input type="email" name="email" id="email" value="{{ old('email', $branch->email) }}" class="form-control @error('email') is-invalid @enderror">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="password" class="col-form-label">{{ __('labels.new_password') }}</label>
                                            <input type="password" name="password" id="password" value="{{ old('password')  }}" class="form-control @error('password') is-invalid @enderror" autocomplete="off">
                                            <small>{!! __('messages.password_format') !!}</small>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="col-form-label">{{ __('labels.new_password_confirmation') }}</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="off">
                                            @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                                @foreach ($active_statuses as $status => $display)
                                                <option value="{{ $status }}" {{ old('status', $branch->status) == $status ? 'selected' : null }}>{{ $display }}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="listing_status" class="col-form-label">{{ __('labels.listing_status') }} <span class="text-danger">*</span></label>
                                            <select name="listing_status" id="listing_status" class="form-control select2 @error('listing_status') is-invalid @enderror">
                                                @forelse ($publish_statuses as $status => $display)
                                                <option value="{{ $status }}" {{ old('listing_status', $branch->listing_status) == $status ? 'selected' : null }}>{{ $display }}</option>
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
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="file" class="col-form-label">{{ __('labels.change_ssm_cert') }}</label>
                                            <input type="file" name="ssm_cert" id="ssm_cert" value="{{ old('ssm_cert') }}" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf">
                                            @error('ssm_cert')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'PDF']) !!}</ul>
                                            <a href="{{ $branch->ssm_cert->full_file_path ?? null }}" download rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $branch->ssm_cert->original_filename ?? '-' }}</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="logo" class="col-form-label">{{ __('labels.change_logo') }}</label>
                                            <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                            @error('logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                            <img src="{{ $branch->logo->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $branch->branchDetail->pic_name ?? null) }}" class="form-control @error('pic_name') is-invalid @enderror">
                                            @error('pic_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-white">+</span>
                                                </div>
                                                <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $branch->branchDetail->pic_contact ?? null)  }}" class="form-control @error('pic_phone') is-invalid @enderror">
                                            </div>
                                            @error('pic_phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="form-group">
                                            <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }} <span class="text-danger">*</span></label>
                                            <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email', $branch->branchDetail->pic_email ?? null) }}" class="form-control @error('pic_email') is-invalid @enderror">
                                            @error('pic_email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div id="location-address-panel" data-route="{{ route('merchant.data.geocoding') }}">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $branch->address->address_1 ?? null) }}">
                                                @error('address_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="address_2" class="col-form-label">{{ __('labels.address_2') }}</label>
                                                <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $branch->address->address_2 ?? null) }}">
                                                @error('address_2')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $branch->address->postcode ?? null) }}">
                                                @error('postcode')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-danger">*</span></label>
                                                <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror city-filter">
                                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country_state', 1))]) }} ---</option>
                                                    @forelse ($country_states as $state)
                                                    <option value="{{ $state->id }}" {{ old('country_state', $branch->address->countryState->id ?? null) == $state->id ? 'selected' : null }}>{{ $state->name }}</option>
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
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-danger">*</span></label>
                                                <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $branch->address->city_id ?? null) }}"
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
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="latitude" class="col-form-label">{{ __('labels.latitude') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $branch->address->latitude ?? 0) }}">
                                                @error('latitude')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="longitude" class="col-form-label">{{ __('labels.longitude') }} <span class="text-danger">*</span></label>
                                                <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $branch->address->longitude ?? 0) }}">
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
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="desription" class="col-form-label">{{ __('labels.description') }}</label>
                                            <textarea name="description" id="description" cols="30" rows="5" maxlength="1000" class="form-control @error('description') is-invalid @enderror">{{ old('description', $branch->branchDetail->description) }}</textarea>
                                            @error('desription')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="services" class="col-form-label">{{ __('labels.services') }}</label>
                                            <textarea name="services" id="services" cols="30" rows="5" maxlength="1000" class="form-control @error('service') is-invalid @enderror">{{ old('services', $branch->branchDetail->services) }}</textarea>
                                            @error('services')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="career_desc" class="col-form-label">{{ __('labels.career_introduction') }}</label>
                                            <textarea name="career_desc" id="career_desc" cols="30" rows="5" maxlength="1000" class="form-control @error('career_desc') is-invalid @enderror">{{ old('career_desc', $branch->branchDetail->career_description) }}</textarea>
                                            @error('career_desc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @if ($max_files > 0)
                                    <div class="col-12">
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
                                    @endif
                                    <div class="col-12">
                                        <div class="form-group">
                                            @include('merchant.components.tbl_image', [
                                            'images' => $image_and_thumbnail,
                                            'thumbnail' => true,
                                            'action' => true,
                                            'sortable' => true,
                                            'reorder_route' => route('merchant.media.reorder'),
                                            'parent_id' => $branch->id,
                                            'parent_type' => 'merchant'
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="tbl_oprating_hour" class="col-form-label">{{ __('labels.operating_hour') }}</label>
                                            @include('components.tbl_operation', ['operation_hours' => $branch->operationHours])
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    @foreach ($social_media as $media_key => $media_text)
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="{{ $media_text }}" class="col-form-label">{{ $media_text }}</label>
                                            <input type="text" name="{{ $media_key }}" id="{{ $media_text }}" value="{{ old($media_key, optional(optional(optional($branch->userSocialMedia)->where('media_key', $media_key))->first())->media_value) }}"
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
                            </div>
                            <div class="tab-pane fade" id="list-reviews" role="tabpanel" aria-labelledby="list-reviews-list">
                                <iframe src="{{ route('merchant.reviews.index', ['merchant_id' => $branch->mainBranch->id, 'branch_id' => $branch->id]) }}" frameborder="0" style="width: 100%; min-height:80vh;"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('merchant.branches.index') }}" role="button" class="btn btn-default mx-2">
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