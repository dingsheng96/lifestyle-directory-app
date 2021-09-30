@extends('admin.layouts.master', ['parent_title' => trans_choice('modules.locale', 2), 'title' => trans_choice('modules.language', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.language', 1)]) }}</span>
                </div>

                <form action="{{ route('admin.locales.languages.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="code" class="col-form-label">{{ __('labels.code') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="code" name="code" value="{{ old('code') }}" class="form-control lcall @error('code') is-invalid @enderror">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="default">{{ __('labels.default') }}</label>
                                    <div class="icheck-purple">
                                        <input type="checkbox" name="default" id="default" {{ old('default') ? "checked" : null }}>
                                        <label for="default"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="new_version" class="col-form-label">{{ __('labels.new_version') }}</label>
                                    <input type="text" id="new_version" name="new_version" value="{{ old('new_version') }}" class="form-control @error('new_version') is-invalid @enderror">
                                    @error('new_version')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="file" class="col-form-label">{{ trans_choice('labels.upload_file', 1) }}</label>
                                    <input type="file" id="file" name="file" class="form-control-file @error('file') is-invalid @enderror">
                                    @error('file')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <ul class="pl-3 mt-3">
                                        {!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}
                                        <li>
                                            {{ __('labels.download_format') }}
                                            <a href="{{ $excel }}" download>here</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.locales.languages.index') }}" class="btn btn-default mx-2">
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