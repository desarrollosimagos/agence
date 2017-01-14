<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|

*/


Route::resource('/','PrincipalController');
Route::get('/relatorio', 'PrincipalController@relatorio');
Route::get('/grafico', 'PrincipalController@grafico');
Route::get('/xml/{mesini}/{anioini}/{mesfin}/{aniofin}/{usuarios}', 'PrincipalController@xml');
Route::get('/pizza', 'PrincipalController@pizza');
Route::get('/xml2/{mesini}/{anioini}/{mesfin}/{aniofin}/{usuarios}', 'PrincipalController@xml2');
