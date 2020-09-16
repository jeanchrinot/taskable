@extends('layouts.app')

@section('head-scripts')
    <script type="text/javascript">
        if (!(localStorage.getItem('userToken'))) {
            window.location.replace('/auth/login');
        }
        if (localStorage.getItem('user_role')!='admin') {
            window.location.replace('/app/dashboard');
        }
    </script>
@endsection
@section('content')
        @include('includes.sidebar')
        
        <section class="content">
            <div class="content__header">
                <h1 class="content__title">Users</h1>
            </div>
            <div class="content__main">
                <div>
                    <users></users>
                </div>
            </div>
            @include('includes.flash')
        </section>
@endsection