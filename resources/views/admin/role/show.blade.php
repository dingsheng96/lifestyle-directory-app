@extends('admin.layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.role', 1)]) }}</span>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span id="name" class="form-control-plaintext">{{ $role->name }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-form-label col-sm-2">{{ __('labels.description') }}</label>
                        <div class="col-sm-10">
                            <span id="description" class="form-control-plaintext">{{ $role->description ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="settings" class="col-form-label col-sm-2">{{ __('labels.settings') }}</label>
                        <div class="col-sm-10" id="settings">
                            <h5>
                                <span class="badge badge-pill badge-light px-3 py-2">
                                    @if ($role->generate_referral)
                                    <i class=" fas fa-check-circle text-success mr-1"></i>
                                    @else
                                    <i class="fas fa-times-circle text-danger mr-1"></i>
                                    @endif
                                    {{ __('labels.generate_referral_code') }}
                                </span>
                            </h5>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="tbl_permission" class="col-form-label">{{ trans_choice('labels.permission', 2) }}</label>
                        <div class="table-responsive" id="tbl_permission">
                            <table class="table table-bordered table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th colspan="5" class="text-center">{{ trans_choice('labels.permission', 2) }}</th>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">{{ trans_choice('labels.module', 2) }}</td>
                                        @foreach ($actions as $action)
                                        <td class="font-weight-bold">
                                            {{ Str::title($action->action) }}
                                        </td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="select-all-container">
                                    @foreach ($modules as $module)
                                    <tr>
                                        <td class="font-weight-bold">{{ $module->display }}</td>
                                        @foreach ($module->permissions as $permission)
                                        <td>
                                            @if (collect($role->permissions->pluck('id'))->contains($permission->id))
                                            <i class="fas fa-check-circle text-success"></i>
                                            @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                            @endif
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
                    <a role="button" href="{{ route('admin.roles.index') }}" class="btn btn-default">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection