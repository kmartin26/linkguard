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

Route::get('/', [
    'as' => 'front.index',
    'uses' => 'HomeController@index',
]);

// About page
Route::get('about', function (){
    return view('about');
});

// Contact form page
Route::get('contact', [
    'as' => 'front.contact.get',
    'uses' => 'ContactController@create',
]);

Route::post('contact', [
    'as' => 'front.contact.post',
    'uses' => 'ContactController@store',
]);

// DMCA form page
Route::get('dmca', [
    'as' => 'front.dmca.get',
    'uses' => 'DmcaController@create',
]);

Route::post('dmca', [
    'as' => 'front.dmca.post',
    'uses' => 'DmcaController@store',
]);

// Link Routes
Route::post('create', [
    'as' => 'front.create',
    'uses' => 'HomeController@create',
]);

Route::get('create', function () {
    return redirect()->route('front.index');
});

Route::get('{id}/delete', [
    'as' => 'front.delete.get',
    'uses' => 'HomeController@getDelete',
]);

Route::post('{id}/delete', [
    'as' => 'front.delete.post',
    'uses' => 'HomeController@postDelete',
]);

Route::get('{id}', [
    'as' => 'front.show.get',
    'uses' => 'HomeController@getShow',
]);

Route::match(['get','post'], '{id}/show', [
    'as' => 'front.show.post',
    'uses' => 'HomeController@postShow',
]);