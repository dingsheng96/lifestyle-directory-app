@extends('admin.layouts.master', ['title' => trans_choice('modules.category', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.category', 1)]) }}</span>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                @forelse ($publish_statuses as $status => $display)
                                <option value="{{ $status }}" {{ old('status', 'publish' )==$status ? 'selected' : null }}>{{ $display }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image" class="col-form-label">{{ trans_choice('labels.upload_image', 1) }} <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-9">
                                    <input type="file" id="image" name="image" class="form-control-file custom-img-input @error('image') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                    @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.categories.index') }}" class="btn btn-default mx-2">
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