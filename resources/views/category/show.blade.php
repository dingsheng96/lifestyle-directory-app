@extends('layouts.master', ['parent_title' => trans_choice('modules.category', 2), 'title' => __('modules.view', ['module' => trans_choice('modules.category', 1)])])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-lg">

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" id="name" value="{{ $category->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="image" class="col-sm-2 col-form-label">{{ trans_choice('labels.image', 1) }}</label>
                        <div class="col-sm-10">
                            <img src="{{ $category->media->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail w-50">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label">{{ __('labels.description') }}</label>
                        <div class="col-sm-10">
                            <textarea type="text" readonly class="form-control-plaintext" id="description" cols="100" rows="7">{{ $category->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('categories.index') }}" class="btn btn-light">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection