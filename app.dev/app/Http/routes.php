<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'test','namespace' => 'Test'],function(){
	Route::get('/',[
		'as' => 'test.index',
		'uses' => 'TestController@index'
	]);

	Route::get('/cat',function(){
		$categories = (new \App\Scrapper\Scrapper())->getCategoryItems(5);
		return dd($categories);
	});

});
