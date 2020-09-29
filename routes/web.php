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

Auth::routes();
Route::get('/pracownik/logowanie', 'Auth\LoginController@showAdminLoginForm');
Route::post('/pracownik/logowanie', 'Auth\LoginController@adminLogin');

Route::get('/', 'IndexController@index');
Route::get('/logowanie', 'IndexController@login');
Route::get('/rejestracja', 'IndexController@register');
Route::get('/kontakt', 'IndexController@contact');
Route::get('/pierwsze-kroki', 'IndexController@firstSteps');
Route::get('/godziny-otwarcia', 'IndexController@workingHours');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/pracownik/wyloguj', 'Auth\LoginController@adminLogout');
    Route::get('/pracownik/czytelnicy/nowy', 'LibrarianController@createUser');
    Route::get('/pracownik', 'LibrarianController@index');

    Route::post('/pracownik/czytelnicy/nowy', 'LibrarianController@storeUser');
    Route::get('/pracownik/czytelnicy/znajdz', 'LibrarianController@findUserView');
    Route::post('/pracownik/czytelnicy/znajdz', 'LibrarianController@findUser');
    Route::get('/pracownik/czytelnicy/{id}', 'LibrarianController@fetchUser');
    Route::post('/pracownik/czytelnicy/{id}/edycja', 'LibrarianController@updateUser');

    Route::get('/pracownik/kategorie', 'CategoryController@index');
    Route::post('/pracownik/kategorie', 'CategoryController@store');

    Route::get('/pracownik/autorzy', 'AuthorController@index');
    Route::post('/pracownik/autorzy', 'AuthorController@store');
    Route::get('/pracownik/autorzy/{id}', 'AuthorController@fetchAuthor');
    Route::post('/pracownik/autorzy/{id}/edycja', 'AuthorController@update');
    Route::post('/pracownik/autorzy/{id}/usun', 'AuthorController@delete');

    Route::get('/pracownik/wydawnictwa', 'PublisherController@index');
    Route::post('/pracownik/wydawnictwa', 'PublisherController@store');
    Route::get('/pracownik/wydawnictwa/{id}', 'PublisherController@fetchPublisher');
    Route::post('/pracownik/wydawnictwa/{id}/edycja', 'PublisherController@update');
    Route::post('/pracownik/wydawnictwa/{id}/usun', 'PublisherController@delete');

    Route::get('/pracownik/info', 'LibrarianController@info');
    Route::get('/pracownik/katalog', 'BookController@index');
    Route::post('/pracownik/katalog', 'BookController@findBook');


    Route::get('/pracownik/ksiazki/nowa', 'BookController@create');
    Route::post('/pracownik/ksiazki/nowa', 'BookController@store');
    Route::get('/pracownik/ksiazki/{id}', 'BookController@fetchOneBook');
    Route::get('/pracownik/ksiazki/{id}/edycja', 'BookController@editBook');
    Route::post('/pracownik/ksiazki/{id}/edycja', 'BookController@update');
});
