@extends('layouts.app')

@section('content')
        @include('includes.sidebar')
        
        <section class="content">
            <div class="content__header">
                <h1 class="content__title">Dashboard</h1>
            </div>
            <div class="content__main">
                <div class="numbers flex">
                    <div class="numbers__item">
                        <div class="numbers__item__icon"><i class="fa fa-user"></i></div>
                        <div class="numbers__item__value"><span class="item__number">14522</span><br><span class="item__label">Users</span></div>
                    </div>
                    <div class="numbers__item">
                        14522
                    </div>
                    <div class="numbers__item">
                        14522
                    </div>
                    <div class="numbers__item">
                        14522
                    </div>
                </div>
                <div>
                    <task-list></task-list>
                </div>
            </div>
            @include('includes.flash')
        </section>
@endsection