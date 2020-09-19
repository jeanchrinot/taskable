<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script type="text/javascript">
            if (localStorage.getItem('userToken')) {
                window.location.replace('/app/dashboard');
            }
        </script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Taskable</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-left">
                <div class="logo">
                    <img src="/img/taskable-logo.png">
                </div>
                <div class="links links--float links--horizontal">
                    <ul>
                        <li><a href="#">How to use<span class="underline"></span></a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact us</a></li>
                    </ul>
                </div>
            </div>
            @if (Route::has('login'))
                <div class="top-right links links--signup">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="button">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="button">Login</a>

                        @if (Route::has('signup'))
                            <a href="{{ route('signup') }}" class="button button--green">Free Signup</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="wrapper--medium">
                    <div class="title m-b-md">
                        Create and Manage your tasks and to-do list.
                    </div>
                    <div>
                       <a href="{{ route('signup') }}" class="button button--green">Try it for free</a>
                    </div>
                </div>
            </div>

            <div>
                <div class="wrapper">
                    <img src="img/taskable-app-screen.png">
                    <!-- <div class="links">
                    <a href="https://laravel.com/docs">Docs</a>
                    <a href="https://laracasts.com">Laracasts</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://forge.laravel.com">Forge</a>
                    <a href="https://vapor.laravel.com">Vapor</a>
                    <a href="https://github.com/laravel/laravel">GitHub</a>
                    </div> -->
                </div>
            </div>
        </div>
        @include('includes.footer')

        <script src="/js/app.js"></script>
    </body>
</html>
