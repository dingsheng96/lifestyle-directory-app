@extends('admin.layouts.master', ['title' => trans_choice('modules.admin', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.admin', 1)]) }}</span>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <p id="name" class="form-control-plaintext">{{ $admin->name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-form-label col-sm-2">{{ __('labels.email') }}</label>
                        <div class="col-sm-10">
                            <p id="email" class="form-control-plaintext">{{ $admin->email }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="role" class="col-form-label col-sm-2">{{ __('labels.role') }}</label>
                        <div class="col-sm-10">
                            <p id="role" class="form-control-plaintext">{{ $admin->roles->first()->name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-2">{{ __('labels.status') }}</label>
                        <div class="col-sm-10">
                            <p id="status" class="form-control-plaintext">{!! $admin->status_label !!}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.admins.index') }}" class="btn btn-light">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection