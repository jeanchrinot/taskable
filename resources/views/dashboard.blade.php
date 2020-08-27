@extends('layouts.app')

@section('content')
        @include('includes.sidebar')

        @php
            $last_task = end($tasks);
        @endphp
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
                <div class="row">
                    <div class="col-md-6 no-padding">
                        <div class="card">
                          <div class="card-header">
                            <i class="fa fa-align-left"></i> {{ $last_task->name }}
                          </div>
                          <div class="card-body">
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" placeholder="Add item to list..." aria-label="Add item to list" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"><i class="fa fa-plus"></i> Add</button>
                              </div>
                            </div>
                            <ul class="todo-list text-left">
                                @foreach($last_task->todos as $todo)
                                <li role="button"><label for="{{ $todo->id }}"> <input type="checkbox" {{ ($todo->complete) ? 'checked="checked"' : ''}} id="{{ $todo->id }}"> <span class="todo-list__text {{ ($todo->complete) ? 'todo-list__item-checked' : ''}}">{{ $todo->name }}</span></label></li>
                                @endforeach
                            </ul>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6 no-padding">
                        <div class="card">
                          <div class="card-header">
                            My Tasks
                          </div>
                          <div class="card-body">
                              <ul class="todo-list text-left">
                                @foreach($tasks as $task)
                                @php
                                    $status = taskStatus($task->id);
                                @endphp
                                <li role="button"><span class="list-style list-style--{{ $status[2] }}"></span> <span class="todo-list__text">{{ $task->name }}</span><span class="badge badge-{{ $status[2] }}" style="float: right;margin-right: 25px;">{{ $status[1] }}</span></li>
                                @endforeach
                            </ul>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>
@endsection