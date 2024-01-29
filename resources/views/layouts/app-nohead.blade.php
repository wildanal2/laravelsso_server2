<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title') -
        {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Bootstrap CSS -->
    <link href="assets/theme/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/theme/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="assets/theme/css/style.css" rel="stylesheet" />
    <link href="assets/theme/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- loader-->
	<link href="assets/theme/css/pace.min.css" rel="stylesheet" />
</head>
<body class="@yield('body-type')">
    <!--start wrapper-->
    <div class="wrapper">
        <!--start content-->
        @yield('content')
        <!--end page main-->
    </div>
    <!--end wrapper-->
    <!-- Bootstrap bundle JS -->
    <script src="assets/theme/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/theme/js/jquery.min.js"></script>
    <script src="assets/theme/js/pace.min.js"></script>
</body>
</html>
