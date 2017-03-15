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

# standard web views

Route::get('/{page?}', 'HomeController@index') -> name('index');



# restful end points

Route::get('/rest/home/{lastId}/{page}', 'HomeController@resthome');