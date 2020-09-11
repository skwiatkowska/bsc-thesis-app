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

Route::get('/', 'IndexController@index');
Route::get('/logowanie', 'IndexController@login');
Route::get('/rejestracja', 'IndexController@register');
Route::get('/kontakt', 'IndexController@contact');
Route::get('/pierwsze-kroki', 'IndexController@firstSteps');
Route::get('/godziny-otwarcia', 'IndexController@workingHours');



Route::get('/zasoby', 'BookController@index');



Route::get('/pracownik/dodaj-ksiazke', 'BookController@create');
Route::post('/pracownik/dodaj-ksiazke', 'BookController@store');

