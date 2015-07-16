<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'IndexController@index');
Route::get('/createProduct', 'IndexController@createProduct');
Route::post('/createProduct', 'IndexController@createProductStep1');
Route::get('/productTemplates', 'IndexController@productTemplates');
Route::get('/editTemplate/{id}', 'IndexController@editTemplate');
Route::get('/create_template', 'IndexController@createTemplate');
Route::post('/createTemplate', 'IndexController@create_template');
Route::post('/saveEditTemplate', 'IndexController@saveEditTemplate');
Route::post('/getTemplate', 'IndexController@getTemplate');
Route::post('/create_productsShopify', 'IndexController@createProductsShopify');
Route::get('/deleteTemplate/{id}', 'IndexController@deleteTemplate');
Route::get('/duplicate/{id}', 'IndexController@duplicateTemplate');

