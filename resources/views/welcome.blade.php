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
        <style type="text/css">
            .header{
                height: 60px;
                float: left;
                display: block;
            }
            .links--horizontal a{
                color: #fff;
            }
            .content{
                width: 100%;
                height: 100%;
                float: left;
                overflow: hidden;
                /*margin-top: 90px;*/
                padding-top: 90px;
                padding-bottom: 30px;
            }
            .page-wrapper{
                width:100%;
                height: 100%;
                background-image: url('img/taskable-app-mobile-screen-500w.png');
              background-repeat: no-repeat;
              background-position: 90% 50%;
            }
            .page-wrapper::after{
                    content: "";
                    width: 100%;
                    display: block;
                    height: 100%;
                    background: rgba(0,0,0,0.6);
                }

            .content__text{
                float: left;
                width: 60%;
                margin-top: 50px;
                padding-left: 20px;
            }
            .content__image{
                float: left;
                width: 50%;
            }

            @media(max-width: 991px){
                .top-left {
                    left: 20px;
                }
                .top-right {
                    right: 20px;
                }

                .content__text {
                    width: 100%;
                    padding-left: 0;
                    padding: 10px;
                }
                .page-wrapper{
                    background-position: center;
                }
                
            }
            @media(max-width: 800px){
                .links--float {    
                    top: 94px;
                    left: 50%;
                    transform: translateX(-87%);
                    display: none;
                }
            }
            @media(max-width: 600px){
                .title {
                    font-size: 55px;
                }
            }

            @media(max-width: 500px){
                .links--float{
                    top: 28px;
                    left: 50%;
                    transform: translateX(-60%);
                }
                .logo {
                    margin-right: 20px;
                }
                .links > a {
                    padding: 0 10px;
                    font-size: 12px;
                }
                .button {
                    min-width: 100px;
                    height: 35px;
                    line-height: 36px;
                }
            }

        </style>
    </head>
    <body>
        <div class="page-wrapper position-ref full-height">
            <div class="header">
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

            </div>
           
            <div class="content">
                <div class="content__text">
                    <div class="wrapper--medium">
                        <div class="title m-b-md">
                            Create and Manage your tasks and to-do list.
                        </div>
                        <div>
                           <a href="{{ route('signup') }}" class="button button--green">Try it for free</a>
                        </div>
                    </div>
                </div>

                <!-- <div class="content__image">
                    <div class="wrapper">
                        <img src="img/taskable-app-screen.png">
                    </div>
                </div> -->
            </div>
        </div>
        @include('includes.footer')

        <script src="/js/app.js"></script>
    </body>
</html>
