@extends('webview.master')

@section('content')

<img src="{{ $banner->media->full_file_path ?? null }}" alt="#" class="webview-img-top">

<div class="container py-3">
    <h4 class="mb-3">{{ $banner->title }}</h4>
    <p>{!! $banner->description !!}</p>
</div>


@endsection