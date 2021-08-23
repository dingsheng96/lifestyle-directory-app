@extends('layouts.master', ['parent_title' => trans_choice('modules.locale', 2), 'title' => trans_choice('modules.language', 2)])

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.language', 1)]) }}</h3>
                </div>

                <form action="{{ route('locale.languages.update', ['language' => $language->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $language->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="code" class="col-form-label">{{ __('labels.code') }} <span class="text-red">*</span></label>
                                    <input type="text" id="code" name="code" value="{{ old('code', $language->code) }}" class="form-control lcall @error('code') is-invalid @enderror">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label for="default">{{ __('labels.default') }}</label>
                                    <div class="icheck-purple">
                                        <input type="checkbox" name="default" id="default" {{ old('default', $language->default) ? "checked" : null }}>
                                        <label for="default"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-12">
                                <a href="#createTranslationModal" class="btn btn-purple" data-toggle="modal">
                                    <i class="fas fa-plus"></i>
                                    {{ __('labels.create') }}
                                </a>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="table-responsive">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('locale.languages.index') }}" class="btn btn-light mx-2">
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

    @include('locale.language.translation.create')
    @include('locale.language.translation.import')

</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush