@extends('layouts.master', ['parent_title' => trans_choice('modules.locale', 2), 'title' => trans_choice('modules.country_state', 2)])

@section('content')

<div class="container-fluid">

    @can('locale.create')
    <div class="row mb-3">
        <div class="col-12">
            <a href="#countryStateModal" class="btn btn-purple" data-toggle="modal">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
        </div>
    </div>
    @endcan

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen(Auth::user()->can('locale.create'), 'locale.country_state.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush