@extends('admin.layouts.master', ['title' => trans_choice('modules.career', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.career', 1)]) }}</span>
                </div>

                <form action="{{ route('admin.careers.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="position" class="col-form-label">{{ __('labels.job_title') }} <span class="text-danger">*</span></label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}" class="form-control ucfirst @error('position') is-invalid @enderror">
                            @error('position')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="merchant" class="col-form-label">{{ __('labels.merchant') }} <span class="text-danger">*</span></label>
                                    <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.merchant'))]) }} ---</option>
                                        @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant')==$merchant->id ? 'selected' : null }}>{{ $merchant->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('merchant')
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
                                        @foreach ($publish_statuses as $status => $display)
                                        <option value="{{ $status }}" {{ old('status', 'publish' )==$status ? 'selected' : null }}>{{ $display }}</option>
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
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="salary" class="col-form-label">{{ __('labels.salary') }} <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-5">
                                            <input type="number" name="min_salary" id="min_salary" step="0.01" min="0" value="{{ old('min_salary', 0) }}" class="form-control @error('min_salary') is-invalid @enderror">
                                        </div>
                                        <div class="col-1 col-form-label text-center">-</div>
                                        <div class="col-6">
                                            <input type="number" name="max_salary" id="max_salary" step="0.01" min="0" value="{{ old('max_salary', 0) }}" class="form-control @error('max_salary') is-invalid @enderror">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="show_salary" class="col-form-label">{{ __('labels.show_salary') }}</label>
                                    <div class="icheck-purple">
                                        <input type="checkbox" name="show_salary" id="show_salary" {{ old('show_salary') ? 'checked' : null }}>
                                        <label for="show_salary"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="col-form-label">{{ __('labels.description') }} <span class="text-danger">*</span></label>
                            <textarea type="text" id="description" name="description" class="form-control summernote @error('description') is-invalid @enderror" cols="100" rows="7">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <hr>
                        <p class="h5">{{ __('labels.applicant_can_apply_via') }}</p>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="col-form-label">{{ __('labels.contact_no') }}</label>
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
                                    <label for="email" class="col-form-label">{{ __('labels.email') }}</label>
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
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.careers.index') }}" class="btn btn-default mx-2">
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