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

Route::get('/papers', 'NewspaperController@index')->name('papers');
Route::post('/papers', 'NewspaperController@index')->name('papers');
Route::get('/paper/{id}', 'NewspaperController@show')->name('paper');
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth', function (Request $request) {
	Route::get('/newpaper', 'NewspaperController@create')->name('newpaper');
});