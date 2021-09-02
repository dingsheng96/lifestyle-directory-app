@extends('webview.master')

@section('content')

<div class="container py-3">

    <div class="mb-3">
        <h4>{{ $notification->title }}</h4>
        <p class="text-secondary">{{ $notification->created_at->format('d M Y') }}</p>
    </div>

    <p>{!! $notification->content !!}</p>
</div>


@endsection