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

Route::group([
    'prefix' => 'auth','namespace' => 'Api'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

});


Route::group([
      'middleware' => 'auth:api'
    ], function() {
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

			Route::get('stats', 'TaskController@stats');

			// Search in Tasks
			//Route::post('tasks/search', 'TaskController@search');

			// List a task todo list
			Route::get('todos/{id}', 'TodoController@index');

			Route::post('todo/toggle/{id}', 'TodoController@toggleComplete');
			Route::post('todo/{id}', 'TodoController@store');
			Route::put('todo/{id}', 'TodoController@update');
			Route::delete('todo/{id}', 'TodoController@destroy');

			Route::get('users','AdminController@users');
			Route::get('user/{id}','AdminController@user');
			Route::put('user/ban/{id}','AdminController@ban');
    });