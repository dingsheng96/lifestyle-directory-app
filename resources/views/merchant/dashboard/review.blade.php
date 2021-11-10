@extends('admin.layouts.master', ['title' => trans_choice('modules.review', 2), 'page_only' => true])

@section('content')

<div class="container-fluid">
    <div class="row mb-3">
        <div class="table-responsive">
            {!! $dataTable->table() !!}
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush