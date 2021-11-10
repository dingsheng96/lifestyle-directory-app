@extends('merchant.layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3>{{ $total_listing_careers }}</h3>
                    <p>{{ __('labels.total_listing_careers') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $today_visitor_count }}</h3>
                    <p>{{ __('labels.today_visitor_count') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $total_visitor_count }}</h3>
                    <p>{{ __('labels.total_visitor_count') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-running"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $average_ratings }}</h3>
                    <p>{{ __('labels.average_ratings') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <h3 class="card-title">{{ trans_choice('labels.review', 2) }}</h3>
                </div>
                <div class="card-body table-responsive">
                    <iframe src="{{ route('merchant.reviews.index') }}" frameborder="0" style="width: 100%; min-height:50vh;"></iframe>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow border">
                <div class="card-header bg-transparent border-0">
                    <h3 class="card-title">{{ __('labels.visitor_history') }}</h3>
                </div>
                <div class="card-body table-responsive">
                    <iframe src="{{ route('merchant.visitors.index') }}" frameborder="0" style="width: 100%; min-height:50vh;"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection