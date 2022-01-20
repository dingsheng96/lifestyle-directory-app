<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@isset ($title) {{ $title . ' |' }} @endisset {{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/web/bootstrap.css?v=' . time()) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/web/style.css?v=' . time()) }}">

</head>

<body class="hold-transition layout-fixed {{ $body ?? '' }}">

    @guest

    @include('layouts.topnav')

    @yield('content')

    @include('layouts.footer')
    @endguest

    <script type="text/javascript" src="{{ asset('js/app.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/web.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/function.js?v=' . time()) }}"></script>
    <script type="text/javascript" src="{{ asset('js/dropdown.js?v=' . time()) }}"></script>
    @stack('scripts')

</body>

</html>