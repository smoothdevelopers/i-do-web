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

App::setLocale('en');
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['web']], function () {

    Route::get('test', function () {

    });

    Route::get('admin/login', 'Auth\AdminLoginController@showLoginForm');
    Route::post('admin/login', 'Auth\AdminLoginController@login')->name('adminLogin');
    Route::post('admin/logout', 'Auth\AdminLoginController@login')->name('adminLogout');

    Route::group(['as' => 'admin.', 'middleware' => ['admin']], function () {

        Route::get('admin/dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('admin/users', 'AdminController@users')->name('users');
        Route::get('admin/single-user/{id}', 'AdminController@singleUser')->name('single-user');

    });

});
Route::get('/home', 'HomeController@index');
App::setLocale('api');
