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
    </div>

</div>

@endsection