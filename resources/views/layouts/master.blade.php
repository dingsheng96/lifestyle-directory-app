<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>

    <link rel="icon" href="{{ asset('storage/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css?v=' . time()) }}">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('dropzone-5.7.0/dist/dropzone.css') }}"> --}}

</head>

<body class="hold-transition layout-fixed text-sm {{ $body ?? '' }}">

    @auth
    <div class="wrapper">
        @include('layouts.topnav')
        @include('layouts.sidenav')

        <div class="content-wrapper">
            @include('layouts.header')
            <div class="content">
                @includeWhen(Session::has('success') || Session::has('fail') ||$errors->any(), 'components.alert')
                @yield('content')
            </div>
        </div>

        @include('layouts.footer') {{-- auth footer --}}

        @include('components.loader')
    </div>
    @endauth

    @guest
    @yield('content')
    @endguest

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script type="text/javascript" src="{{ asset('dropzone-5.7.0/dist/dropzone.js?v=' . time()) }}"></script> --}}
    <script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/style.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/modal.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropzone.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dynamic-form.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>