@extends('layouts.app')

@section('head-scripts')
    <script type="text/javascript">
        if (!(localStorage.getItem('userToken'))) {
            window.location.replace('/auth/login');
        }
    </script>
@endsection
@section('content')
        
        <section class="content">
            <div class="content__header">
                <div class="sidebar__nav__head">
                    <div class="title--small">
                        MAIN NAVIGATION
                    </div>
                    <div class="sidebar__menu__icon sidebar__menu__icon--close-x nav__icon">
                        <span class="sidebar__menu__icon--middle"></span>
                    </div>
                </div>
                <div class="content__title">
                    <h1 class="content__title__text">Dashboard</h1>
                </div>
            </div>
            <div class="content__main">
                <div class="content__main__left content__main__left--expanded">
                    @include('includes.sidebar')
                </div>
                <div class="content__main__right">
                    <stats></stats>
                    <div>
                        <task-list></task-list>
                    </div>
                </div>
            </div>
            @include('includes.flash')
        </section>
@endsection