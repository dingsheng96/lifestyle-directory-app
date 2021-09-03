@extends('admin.layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow">

                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.view', ['module' => trans_choice('modules.role', 1)]) }}</span>
                </div>

                <div class="card-body">

                    <div class="form-group">
                        <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                        <p id="name" class="form-control">{{ $role->name }}</p>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                        <textarea id="description" cols="100" rows="7" class="form-control bg-white" disabled>{{ $role->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="tbl_permission" class="col-form-label">{{ trans_choice('labels.permission', 2) }}</label>
                        <div class="table-responsive" id="tbl_permission">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
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
                    <a role="button" href="{{ route('admin.roles.index') }}" class="btn btn-light">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection