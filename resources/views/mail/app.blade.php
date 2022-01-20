<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>

    <style>
        .mail-container {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .mail-header {
            background-color: #f6993f;
            height: 100px;
        }

        .mail-header th {
            padding: 50px;
        }

        .mail-header img.mail-header-img {
            margin-left: auto;
            margin-right: auto;
            object-fit: scale-down;
            display: block;
            height: 100%;
            width: 12rem;
        }

        .mail-body {
            font-size: large;
        }

        .mail-body td {
            padding: 20px 10px;
        }

        .mail-text {
            font-size: 20px;
        }

        .mail-title {
            font-size: 40px;
        }

        .mail-button {
            padding: 15px 40px;
            background-color: #f6993f;
            color: #ffffff;
            font-size: 16px;
            transition-duration: 0.4s;
            border-color: #f7a657;
            border-style: solid;
        }

        .mail-button:hover {
            background-color: #f47f0e;
        }

        .text-center {
            text-align: center;
        }

        .w-100 {
            width: 100%;
        }

        .w-75 {
            width: 75%;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .list-style-none {
            list-style-type: none;
            padding-inline-start: 0px;
        }

        @media screen and (max-width: 768px) {
            .mail-container {
                width: 750px;
            }
        }

        @media (min-width: 992px) {
            .mail-container {
                width: 970px;
            }
        }

        @media (min-width: 1200px) {
            .mail-container {
                width: 1170px;
            }
        }
    </style>
</head>

<body>

    <table class="w-100 mail-container">
        <tr>
            <td>@yield('content')</td>
        </tr>
    </table>

</body>

</html>