@extends('layouts.master', ['title' => trans_choice('modules.admin', 2)])

@section('content')

<div class="container-fluid">
    @can('admin.create')
    <div class="row">
        <div class="col-12">
            <a href="#createAdminModal" class="btn btn-purple" data-toggle="modal">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }}
            </a>
        </div>
    </div>
    @endcan

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


@includeWhen(Auth::user()->can('admin.create'), 'admin.create', compact('statuses'))
@includeWhen(Auth::user()->can('admin.update'), 'admin.edit', compact('statuses'))

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}

@if ($errors->has('create.*'))
<script type="text/javascript">
    $(window).on('load', function () {
        $('#createAdminModal').modal('show');
    });
</script>
@endif

@if ($errors->has('update.*'))
<script type="text/javascript">
    $(window).on('load', function () {
        $('#updateAdminModal').modal('show');
    });
</script>
@endif
@endpush