@extends('layouts.master', ['title' => trans_choice('modules.role', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            @can('role.create')
            <a href="#roleModal" class="btn btn-purple" data-toggle="modal">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
            @endcan
        </div>
    </div>

    <div class="row py-2">
        <div class="col-12">
            <div class="card shadow-lg">

                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen(Auth::user()->can('role.create'), 'role.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush