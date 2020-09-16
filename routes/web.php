<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/auth/login',function ()
{
	return view('auth.login');
})->name('login');

Route::get('/auth/register',function ()
{
	return view('auth.register');
})->name('register');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/roles', 'PermissionController@Permission'); // Use this to add dummy data of roles and permissions 
Route::get('/test','TestController@index');
// Route::group(array('prefix' => 'user', 'middleware' => 'role:user'), function()
// {
// 	Route::get('dashboard', 'DashboardController@index')->name('user.dashboard');
// 	Route::get('tasks', 'DashboardController@list')->name('user.tasks');
// });

Route::group(array('prefix' => 'app'), function()
{
	Route::get('dashboard', 'DashboardController@index')->name('app.dashboard');
	Route::get('tasks', 'DashboardController@list')->name('app.tasks');
	Route::get('profile', 'DashboardController@profile')->name('user.profile');
	Route::get('users', 'DashboardController@users')->name('app.users');
});

