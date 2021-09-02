@extends('webview.master', ['title' => trans_choice('modules.banner', 2)])

@section('content')

<img src="{{ $banner->media->full_file_path ?? null }}" alt="#" class="webview-img-top">

<div class="container py-3">
    <h3 class="mb-3">{{ $banner->title }}</h3>
    <p>{!! $banner->description !!}</p>
</div>


@endsection