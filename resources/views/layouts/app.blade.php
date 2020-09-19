
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark-bg">
<head>
    @yield('head-scripts')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome Css -->
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="dark-bg">
    <div class="page-container" id="app" style="height: 90%;">
        <div class="content-wrap">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <div class="logo">
                        <a class="navbar-brand" href="{{ url('/') }}">
                        <img class="logo-img" src="/img/taskable-logo-horizontal.png">
                        </a>
                    </div>
                    
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">

                        </ul>

                        <auth-navbar></auth-navbar>
                    </div>
                </div>
            </nav>

            <main>
                 <div class="all-content-wrapper">
                @yield('content')
                </div>
            </main>
        </div>
        @include('includes.footer')
    </div>

    <script src="/assets/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="/assets/plugins/metisMenu/dist/metisMenu.js"></script>
</body>
</html>
