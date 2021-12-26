<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title') | エアバッグ類引取検品記録システム
    </title>

    <!-- CSS Files -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/paper-dashboard.css') }}" rel="stylesheet" />

    <!-- page CSS -->
    <link href="{{ mix('css/screens/login.css') }}" rel="stylesheet" />

    {{-- custom/common css --}}
    <link href="{{ mix('css/common.css') }}" rel="stylesheet" />
    <link href="{{ mix('css/custom.css') }}" rel="stylesheet" />
</head>

<body>
    @yield('content')

    <script src="{{ asset('js/core/jquery.min.js') }}"></script>

    <script src="{{ asset('js/library/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/library/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/library/jquery-validation/additional-setting.js') }}"></script>

    @stack('scripts')
</body>

</html>
