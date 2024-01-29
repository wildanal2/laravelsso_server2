<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="semi-dark">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="assets/theme/images/favicon-32x32.png" type="image/png" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    
    <!--plugins-->
    <link href="assets/theme/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="assets/theme/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/theme/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/theme/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="assets/theme/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/theme/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="assets/theme/css/style.css" rel="stylesheet" />
    <link href="assets/theme/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">


    <!-- loader-->
    <link href="assets/theme/css/pace.min.css" rel="stylesheet" />

    <!--Theme Styles-->
    <link href="assets/theme/css/dark-theme.css" rel="stylesheet" />
    <link href="assets/theme/css/light-theme.css" rel="stylesheet" />
    <link href="assets/theme/css/semi-dark.css" rel="stylesheet" />
    <link href="assets/theme/css/header-colors.css" rel="stylesheet" />
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
    <script src="assets/theme/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/theme/js/jquery.min.js"></script>
    <script src="assets/theme/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/theme/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/theme/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/theme/js/pace.min.js"></script>
    <script src="assets/theme/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/theme/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/theme/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
    <!-- Vector map JavaScript -->
    <script src="assets/theme/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/theme/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!--app-->
    <script src="assets/theme/js/app.js"></script>
    <script src="assets/theme/js/index.js"></script>
</body>

</html>