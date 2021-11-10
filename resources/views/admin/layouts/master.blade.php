<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} @if (!empty($title)) {{ ' | ' . $title }} @endif</title>

    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css?v=' . time()) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" integrity="sha512-3JRrEUwaCkFUBLK1N8HehwQgu8e23jTH4np5NHOmQOobuC4ROQxFwFgBLTnhcnQRMs84muMh0PnnwXlPq5MGjg==" crossorigin="anonymous" />

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @auth
    <div class="wrapper">
        @includeWhen((!isset($page_only) || !$page_only), 'admin.layouts.topnav')
        @includeWhen((!isset($page_only) || !$page_only), 'admin.layouts.sidenav')

        <div class="content-wrapper">

            @includeWhen((!isset($page_only) || !$page_only), 'admin.layouts.header')

            <div class="content">
                @includeWhen(Session::has('success') || Session::has('fail') ||$errors->any(), 'components.alert')
                @yield('content')
            </div>
        </div>

        @includeWhen((!isset($page_only) || !$page_only), 'admin.layouts.footer') {{-- auth footer --}}

        @include('components.loader')
    </div>
    @endauth

    @guest
    @yield('content')
    @endguest

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js" integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ asset('js/loader.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/datepicker.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/style.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/modal.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropzone.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/geocoder.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/sortable.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>