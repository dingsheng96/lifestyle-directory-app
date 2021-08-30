@extends('layouts.master', ['title' => trans_choice('modules.admin', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.admin', 1)]) }}</span>
                </div>

                <form action="{{ route('admins.store') }}" method="post" enctype="multipart/form-data" role="form">
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
                            <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                @foreach ($active_statuses as $index => $status)
                                <option value="{{ $index }}" {{ old('status', 'active') == $index ? 'selected' : null }}>{{ $status}}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label">{{ __('labels.password') }} <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" autocomplete="off">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small>* <strong>{{ __('messages.password_format') }}</strong></small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">{{ __('labels.password_confirmation') }} <span class="text-danger">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="off">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admins.index') }}" class="btn btn-light mx-2">
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