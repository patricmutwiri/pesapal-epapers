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
	
Route::get('/paynow', 'OrdersController@payment')->name('paynow');
Route::post('/paynow', 'OrdersController@payment')->name('paynow');

Route::group(['middleware' => 'auth'], function () {
	Route::get('file/{id}', ['as' => 'file', 'uses'=>'HomeController@getFile']);
	Route::get('/newpaper', 'NewspaperController@create')->name('newpaper');
	Route::get('/orders', 'OrdersController@index')->name('orders');
	Route::get('/order/{id}', 'OrdersController@show')->name('order');
});

Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'OrdersController@paymentsuccess']);
Route::get('paymentconfirmation', 'OrdersController@paymentconfirmation');

Route::post('donepayment', ['as' => 'paymentsuccess', 'uses'=>'OrdersController@paymentsuccess']);
Route::post('paymentconfirmation', 'OrdersController@paymentconfirmation');

Route::group(['prefix' => '/webhooks'], function () {
    //PESAPAL
    Route::get('donepayment', ['as' => 'paymentsuccess', 'uses'=>'OrdersController@paymentsuccess']);
    Route::get('paymentconfirmation', 'OrdersController@paymentconfirmation');
});