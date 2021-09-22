@extends('admin.layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    {{-- Widgets --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_pending_applications }}</h3>
                    <p>{{ __('labels.total_pending_applications') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stamp"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $total_registered_members }}</h3>
                    <p>{{ __('labels.total_registered_members') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $total_listing_merchants }}</h3>
                    <p>{{ __('labels.total_listing_merchants') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-store"></i>
                </div>
            </div>
        </div>

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

    {{-- Top 10 rated merchants --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header border-0 bg-transparent">
                    <span class="h5">{{ __('labels.top_rated_merchants') }}</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover w-100">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('labels.rank') }}</th>
                                    <th scope="col">{{ __('labels.name') }}</th>
                                    <th scope="col">{{ __('labels.average_ratings') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($top_rated_merchants as $merchant)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $merchant->name }}</td>
                                    <td>{{ $merchant->rating }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">{{ __('messages.no_records') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection