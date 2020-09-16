@extends('layouts.app')

@section('head-scripts')
    <script type="text/javascript">
        if (!(localStorage.getItem('userToken'))) {
            window.location.replace('/auth/login');
        }
    </script>
@endsection
@section('content')
        @include('includes.sidebar')
        
        <section class="content">
            <div class="content__header">
                <h1 class="content__title">Dashboard</h1>
            </div>
            <div class="content__main">
                <stats></stats>
                <div>
                    <task-list></task-list>
                </div>
            </div>
            @include('includes.flash')
        </section>
@endsection