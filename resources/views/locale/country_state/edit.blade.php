@extends('layouts.master', ['parent_title' => trans_choice('modules.locale', 2), 'title' => trans_choice('modules.country_state', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ __('modules.edit', ['module' => trans_choice('modules.country_state', 1)]) }}</span>
                </div>

                <form action="{{ route('locale.country-states.update', ['country_state' => $country_state->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $country_state->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('locale.country-states.index') }}" class="btn btn-light mx-2">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
                        </a>
                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h3 class="card-title mt-2 font-weight-bold">{{ trans_choice('labels.city', 2) }}</h3>
                    <div class="card-tools">
                        <a href="#cityModal" class="btn btn-purple" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('labels.create') }}
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@include('locale.country_state.city.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush