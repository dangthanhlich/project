<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @yield('title') | エアバッグ類引取検品記録システム
    </title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/font/PingFang SC Regular.css') }}">

    <!-- CSS Files -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/paper-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/table.css') }}" rel="stylesheet" />

    <!-- iCheck -->
    <link href="{{ asset('css/library/icheck-1.0.3/skins/all.css') }}" rel="stylesheet">

    <!-- chosen select -->
    <link href="{{ asset('css//library/chosen/chosen.css') }}" rel="stylesheet" />

    {{-- date picker --}}
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}" />

    <!-- common CSS -->
    <link href="{{ mix('css/common.css') }}" rel="stylesheet" />

    {{-- custom css --}}
    <link href="{{ mix('css/custom.css') }}" rel="stylesheet" />

    @stack('style')
</head>

<body>
    <div class="wrapper">
        @if(auth()->user() && !checkScreenNoNeedLogin())
            <div class="sidebar" data-color="white" data-active-color="danger">
                @include('layouts.partials.header')
                @include('layouts.partials.menu')
            </div>
        @endif

        <div class="main-panel" style="{{ checkScreenNoNeedLogin() ? 'width: calc(100% - 0px) !important;' : '' }}">
            <!-- Navbar -->
            @if(auth()->user() && !checkScreenNoNeedLogin())
                @include('layouts.partials.navbar')
            @else
                <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
                    <div class="container-fluid">
                        <div class="navbar-wrapper">
                            <span class="navbar-brand">@yield('title')</span>
                        </div>
                    </div>
                </nav>
            @endif
            <!-- End Navbar -->

            <div class="content">
                @if(Session::has('error'))
                    @php
                        $errorType = 'danger';
                        $errorMessages = Session::get('error');
                    @endphp
                    <x-alert :messages="$errorMessages" :type="$errorType" />
                @endif

                @if(Session::has('success'))
                    @php
                        $successType = 'success';
                        $successMessages = Session::get('success');
                    @endphp
                    <x-alert :messages="$successMessages" :type="$successType" />
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <div id="loading" style="display: none">
        <div class="lds-dual-ring"></div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('js/core/jquery.min.js') }}"></script>
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>

    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('js/paper-dashboard.min.js') }}" type="text/javascript"></script>

    <!-- iCheck -->
    <script src="{{ asset('js/library/icheck-1.0.3/icheck.min.js') }}"></script>

    {{-- date picker --}}
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script src="{{ asset('js/datepicker.ja-JP.js') }}"></script>

    <!-- datatables -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- 日付計算 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <!-- chosen select -->
    <script src="{{ asset('js/library/chosen/chosen.jquery.js') }}"></script>

    <script src="{{ asset('js/library/url.min.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- common JS -->
    <script src="{{ mix('js/common.js') }}"></script>
    <script src="{{ mix('js/custom.js') }}"></script>

    <script src="{{ asset('js/library/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/library/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ mix('js/library/jquery-validation/additional-setting.js') }}"></script>

    @stack('scripts')
</body>

</html>
