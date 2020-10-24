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

Route::get('/logowanie','Auth\LoginController@showUserLoginForm');
Route::post('/logowanie','Auth\LoginController@userLogin');
Route::get('/rejestracja', 'Auth\RegisterController@showUserRegisterForm');
Route::post('/rejestracja', 'Auth\RegisterController@createUser');



Route::get('/', 'HomeController@index');
Route::get('/kontakt', 'HomeController@contact');
Route::get('/pierwsze-kroki', 'HomeController@firstSteps');
Route::get('/godziny-otwarcia', 'HomeController@workingHours');
Route::get('/katalog', 'User\BookController@index');
Route::post('/katalog', 'User\BookController@findBook');

Route::group(['middleware' => 'user'], function () {
    Route::get('/wyloguj', 'Auth\LoginController@userLogout')->name('logout');
    Route::post('/reset','Auth\ResetPasswordController@changePassword')->name('changePassword');
    Route::get('/dane', 'User\UserController@userInfo');
});



Route::group(['middleware' => 'admin'], function () {
    Route::get('/pracownik/wyloguj', 'Auth\LoginController@adminLogout')->name('logout');
    Route::get('/pracownik/czytelnicy/nowy', 'Admin\UserController@createUser');
    Route::get('/pracownik', 'Admin\HomeController@index');

    Route::get('/pracownik/info', 'Admin\HomeController@info');

    Route::post('/pracownik/czytelnicy/nowy', 'Admin\UserController@storeUser');
    Route::get('/pracownik/czytelnicy/znajdz', 'Admin\UserController@findUserView');
    Route::post('/pracownik/czytelnicy/znajdz', 'Admin\UserController@findUser');
    Route::get('/pracownik/czytelnicy/{id}', 'Admin\UserController@fetchUser');
    Route::post('/pracownik/czytelnicy/{id}/edycja', 'Admin\UserController@updateUser');

    Route::get('/pracownik/kategorie', 'Admin\CategoryController@index');
    Route::post('/pracownik/kategorie', 'Admin\CategoryController@store');

    Route::get('/pracownik/autorzy', 'Admin\AuthorController@index');
    Route::post('/pracownik/autorzy', 'Admin\AuthorController@store');
    Route::get('/pracownik/autorzy/{id}', 'Admin\AuthorController@fetchAuthor');
    Route::post('/pracownik/autorzy/{id}/edycja', 'Admin\AuthorController@update');
    Route::post('/pracownik/autorzy/{id}/usun', 'Admin\AuthorController@delete');

    Route::get('/pracownik/wydawnictwa', 'Admin\PublisherController@index');
    Route::post('/pracownik/wydawnictwa', 'Admin\PublisherController@store');
    Route::get('/pracownik/wydawnictwa/{id}', 'Admin\PublisherController@fetchPublisher');
    Route::post('/pracownik/wydawnictwa/{id}/edycja', 'Admin\PublisherController@update');
    Route::post('/pracownik/wydawnictwa/{id}/usun', 'Admin\PublisherController@delete');

    Route::get('/pracownik/katalog', 'Admin\BookController@index');
    Route::post('/pracownik/katalog', 'Admin\BookController@findBook');

    Route::get('/pracownik/ksiazki/nowa', 'Admin\BookController@create');
    Route::post('/pracownik/ksiazki/nowa', 'Admin\BookController@store');
    Route::get('/pracownik/ksiazki/{id}', 'Admin\BookController@fetchOneBook');
    Route::post('/pracownik/ksiazki/{id}/usun', 'Admin\BookController@deleteBook');
    Route::get('/pracownik/ksiazki/{id}/edycja', 'Admin\BookController@editBook');
    Route::post('/pracownik/ksiazki/{id}/edycja', 'Admin\BookController@update');
    Route::post('/pracownik/ksiazki/{id}/nowy-egzemplarz', 'Admin\BookController@storeBookItem');

    Route::get('/pracownik/egzemplarze/{id}', 'Admin\BookController@fetchBookItem');
    Route::post('/pracownik/egzemplarze/{id}/blokuj', 'Admin\BookController@blockUnlockBookItem');
    Route::post('/pracownik/egzemplarze/{id}/usun', 'Admin\BookController@deleteBookItem');
    Route::get('/pracownik/egzemplarze/{id}/wypozycz', 'Admin\BorrowingController@borrowBookItemAddUser');
    Route::post('/pracownik/egzemplarze/{id}/wypozycz', 'Admin\BorrowingController@borrowBookItemFindUser');
    Route::post('/pracownik/egzemplarze/{id}/wypozycz/zapisz', 'Admin\BorrowingController@borrowBook');
    Route::post('/pracownik/egzemplarze/{id}/prolonguj', 'Admin\BorrowingController@prolongBookItem');
    Route::post('/pracownik/egzemplarze/{id}/zwroc', 'Admin\BorrowingController@returnBookItem');

    Route::get('/pracownik/wypozyczenia', 'Admin\BorrowingController@index');

    Route::get('/pracownik/rezerwacje', 'Admin\ReservationController@index');
    Route::post('/pracownik/egzemplarze/{id}/rezerwacja/wypozycz', 'Admin\ReservationController@borrowReservedBook');


});
