@extends('admin.layouts.master', ['title' => trans_choice('modules.career', 2)])

@section('content')

<div class="container-fluid">

    @can('career.create')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.careers.create') }}" class="btn btn-purple">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
        </div>
    </div>
    @endcan

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush