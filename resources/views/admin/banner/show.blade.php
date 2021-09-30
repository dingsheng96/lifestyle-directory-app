@extends('admin.layouts.master', ['title' => trans_choice('modules.banner', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.banner', 1)]) }}</span>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">{{ __('labels.title') }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="title" value="{{ $banner->title }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">
                            <span class="status">{!! $banner->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="image" class="col-sm-2 col-form-label">{{ trans_choice('labels.image', 1) }}</label>
                        <div class="col-sm-10">
                            <a href="{{ $banner->media->full_file_path }}" target="_blank" rel="noopener noreferrer"><i class="fas fa-external-link-alt mr-2"></i>{{ $banner->media->original_filename }}</a>
                            <br>
                            <img src="{{ $banner->media->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail w-50">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">{{ __('labels.description') }}</label>
                        <div class="col-sm-10">
                            <textarea type="text" readonly class="form-control-plaintext" id="description" cols="100" rows="7">{{ $banner->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.banners.index') }}" class="btn btn-default">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection