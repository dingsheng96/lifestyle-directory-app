@extends('admin.layouts.master', ['title' => trans_choice('modules.configuration', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-3">
            <div class="list-group trigger-by-hash-tab" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-general-list" data-toggle="list" href="#list-general" role="tab" aria-controls="general">{{ trans('labels.general') }}</a>
            </div>
        </div>
        <div class="col-12 col-sm-9">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <span class="h5">{{ trans_choice('modules.configuration', 2) }}</span>
                </div>

                <form action="{{ route('admin.configs.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

                    <div class="card-body">

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
                                <div class="row">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="search_radius_in_km" class="col-form-label">{{ trans('labels.search_radius_in_km') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="search_radius_in_km" id="search_radius_in_km" class="form-control @error('search_radius_in_km') is-invalid @enderror" min="5" value="5.00" step="0.01">
                                            @error('search_radius_in_km')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="review_idle_period_in_days" class="col-form-label">{{ trans('labels.review_idle_period_in_days') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="review_idle_period_in_days" id="review_idle_period_in_days" class="form-control @error('review_idle_period_in_days') is-invalid @enderror" min="0" value="0" step="1">
                                            @error('review_idle_period_in_days')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <button type="submit" class="btn btn-purple">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script type="text/javascript">
    let configs = @json($configs);
    $.each(configs, function (key, value) {
        let element = $(document).find("[name=" + key + "]");
        if(element.is('select')) {
            element.val(value).trigger('change');
        } else {
            element.val(value)
        }
    });
</script>

@endpush