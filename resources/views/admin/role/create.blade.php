@extends('admin.layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.create', ['module' => trans_choice('modules.role', 1)]) }}</span>
                </div>

                <form action="{{ route('admin.roles.store') }}" method="post" enctype="multipart/form-data" role="form">
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
                            <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                            <textarea name="description" id="description" cols="100" rows="7" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="settings" class="col-form-label">{{ __('labels.settings') }}</label>
                            <div class="row mt-3" id="settings">
                                <div class="col-12">
                                    <div class="icheck-purple d-inline">
                                        <input type="checkbox" id="generate_referral" name="generate_referral" value="1" {{ old('generate_referral') == 1 ? 'checked' : null }}>
                                        <label for="generate_referral">{{ __('labels.generate_referral_code') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="table-responsive" id="tbl_permission">
                                <table class="table table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th colspan="5" class="text-center">{{ trans_choice('labels.permission', 2) }}</th>
                                        </tr>
                                        <tr>
                                            <th>{{ trans_choice('labels.module', 2) }}</th>
                                            @foreach ($actions as $action)
                                            <th>
                                                {{ Str::title($action->action) }}
                                            </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="select-all-container">
                                        @foreach ($modules as $index => $module)
                                        <tr>
                                            <td class="font-weight-bold">{{ $module->display }}</td>
                                            @foreach ($module->permissions as $permission)
                                            <td>
                                                <div class="icheck-purple">
                                                    <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" {{ collect(old("permission_{$index}"))->contains($permission->id) ? 'checked' : null }}>
                                                    <label for="permission_{{ $permission->id }}">{{ $permission->display }}</label>
                                                </div>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.roles.index') }}" class="btn btn-default mx-2">
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