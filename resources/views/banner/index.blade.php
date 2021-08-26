@extends('layouts.master', ['title' => trans_choice('modules.banner', 2)])

@section('content')

<div class="container-fluid">

    @can('banner.create')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('banners.create') }}" class="btn btn-purple">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
        </div>
    </div>
    @endcan

    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
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