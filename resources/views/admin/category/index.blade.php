@extends('admin.layouts.master', ['title' => trans_choice('modules.category', 2)])

@section('content')

<div class="container-fluid">

    @can('category.create')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-purple">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
        </div>
    </div>
    @endcan

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">
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