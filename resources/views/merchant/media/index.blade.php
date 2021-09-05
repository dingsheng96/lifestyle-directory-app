@extends('merchant.layouts.master', ['title' => __('modules.media')])

@section('content')

<div class="container-fluid">

    @if ($max_files > 0)
    <div class="row mb-3">
        <div class="col-12">
            <a href="#uploadMediaModal" class="btn btn-purple" data-toggle="modal">
                <i class="fas fa-plus"></i>
                {{ __('labels.upload') }}
            </a>
        </div>
    </div>
    @endif

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

@include('merchant.media.upload', compact('max_files'))

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush