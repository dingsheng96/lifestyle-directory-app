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
            <div class="card shadow border">
                <div class="card-body table-responsive">
                    @include('merchant.components.tbl_image', [
                    'images' => $user->media,
                    'thumbnail' => true,
                    'action' => true,
                    'delete_permission' => 'merchant.destroy',
                    'sortable' => true,
                    'reorder_route' => route('merchant.media.reorder'),
                    'parent_id' => $user->id,
                    'parent_type' => 'merchant'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>

@include('merchant.media.upload', compact('max_files'))

@endsection