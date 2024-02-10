<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="semi-dark">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ url('') }}/assets/theme/images/favicon-32x32.png" type="image/png" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') -
        {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Scripts --> 
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!--plugins-->
    <link rel="stylesheet" href="{{ url('') }}/assets/theme/plugins/notifications/css/lobibox.min.css" />
    <link href="{{ url('') }}/assets/theme/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ url('') }}/assets/theme/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/style.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    
    <!-- loader-->
    <link href="{{ url('') }}/assets/theme/css/pace.min.css" rel="stylesheet" />

    <!--Theme Styles-->
    <link href="{{ url('') }}/assets/theme/css/dark-theme.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/light-theme.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/semi-dark.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/theme/css/header-colors.css" rel="stylesheet" />
    
    @vite(['resources/css/app.css'])

    @yield('head')
</head>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        @include('components.top-header')
        <!--end top header-->

        <!--start sidebar -->
        @include('components.sidebar')
        <!--end sidebar -->

        <!--start content-->
        <main class="page-content">
            @yield('content')
        </main>
        <!--end page main-->

        <!--start overlay-->
        <div class="overlay nav-toggle-icon"></div>
        <!--end overlay-->

        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->

        <!--start switcher-->
        @include('components.theme-switcher')
        <!--end switcher-->

    </div>
    <!--end wrapper-->


    <!-- Bootstrap bundle JS -->
    {{-- <script src="{{ url('') }}/assets/theme/js/bootstrap.bundle.min.js"></script> --}}
    <!--plugins-->
    <script src="{{ url('') }}/assets/theme/js/jquery.min.js"></script>
    <script src="{{ url('') }}/assets/theme/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ url('') }}/assets/theme/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ url('') }}/assets/theme/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="{{ url('') }}/assets/theme/js/pace.min.js"></script>
    <!--notification js -->
    <script src="{{ url('') }}/assets/theme/plugins/notifications/js/lobibox.min.js"></script>
    <script src="{{ url('') }}/assets/theme/plugins/notifications/js/notifications.js"></script>
    <!--app-->
    <script src="{{ url('') }}/assets/theme/js/app.js"></script>
    <script src="{{ url('') }}/assets/theme/js/index.js"></script>
    <script src="{{ asset('assets/theme/js/select2.full.min.js') }}"></script>

    @yield('script')
    @if (!empty(session('message')))
    <script>
        Lobibox.notify("{{ session('code') == 200 ? 'success' : 'error' }}", {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            msg: "{{ session('message') }}"
        });
    </script>
    @endif
</body>

</html>