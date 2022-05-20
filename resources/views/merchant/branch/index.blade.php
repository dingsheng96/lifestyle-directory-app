@extends('merchant.layouts.master', ['title' => trans_choice('modules.company', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <h3 class="card-title">{{ trans('labels.main_hq') }}</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table w-100 table-hover table-head-fixed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ trans('labels.name') }}</th>
                                <th>{{ trans('labels.email') }}</th>
                                <th>{{ trans('labels.location') }}</th>
                                <th>{{ trans('labels.status') }}</th>
                                <th>{{ trans('labels.created_at') }}</th>
                                <th>{{ trans('labels.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $user = Auth::user()->load('address'); @endphp
                            <tr>
                                <td>1</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{!! $user->address->location_city_state !!}</td>
                                <td>{!! $user->status_label !!}</td>
                                <td>{!! $user->created_at->toDateTimeString() !!}</td>
                                <td>
                                    @include('merchant.components.btn_action', [
                                    'view' => [
                                    'route' => route('merchant.merchants.show', ['merchant' => $user->id])
                                    ],
                                    'update' => [
                                    'route' => route('merchant.merchants.edit', ['merchant' => $user->id])
                                    ]
                                    ])
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <a href="#importBranchModal" class="btn btn-info mr-1" data-toggle="modal">
                <i class="fas fa-file-import"></i>
                {{ __('labels.import') }} {{ trans_choice('labels.branch', 1) }}
            </a>
            <a href="{{ route('merchant.branches.create') }}" class="btn btn-purple">
                <i class="fas fa-plus"></i>
                {{ __('labels.create') }} {{ trans_choice('labels.branch', 1) }}
            </a>
        </div>
        <div class="col-12 mb-3">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <h3 class="card-title">{{ trans_choice('labels.branch', 2) }}</h3>
                </div>
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@include('merchant.branch.import')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush