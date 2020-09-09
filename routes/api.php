<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// List all tasks
Route::get('tasks', 'TaskController@index');
 
// List a single task
Route::get('task/{id}', 'TaskController@show');
 
// Create a new task
Route::post('task', 'TaskController@store');
 
// Update a task
Route::put('task/{id}', 'TaskController@update');
 
// Delete a task
Route::delete('task/{id}', 'TaskController@destroy');

// List a task todo list
Route::get('todos/{id}', 'TodoController@index');

Route::post('todo/toggle/{id}', 'TodoController@toggleComplete');
Route::post('todo/{id}', 'TodoController@store');
Route::put('todo/{id}', 'TodoController@update');
Route::delete('todo/{id}', 'TodoController@destroy');