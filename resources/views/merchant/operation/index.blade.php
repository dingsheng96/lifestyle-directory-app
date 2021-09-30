@extends('merchant.layouts.master', ['title' => trans_choice('modules.operation', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.edit', ['module' => trans_choice('modules.operation', 1)]) }}</span>
                </div>

                <form action="{{ route('merchant.operations.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        @include('components.tbl_operation', ['operation_hours' => $user->operationHours])
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
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