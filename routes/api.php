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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::match(['get','post'], 'create', [
    'as' => 'api.create',
    'uses' => 'CoreController@create',
]);

Route::delete('delete', [
    'as' => 'api.delete',
    'uses' => 'CoreController@delete',
]);