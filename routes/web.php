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

Route::get('/', ['as'=>'home', function () {
    return view('main');
}]);


Route::group(['prefix'=>'auth', 'as'=>'user.'], function(){
    Route::get('register', [
        'as' => 'create',
        'uses' => 'Auth\RegisterController@showRegistrationForm'
    ]);
    Route::post('register', [
        'as' => 'store',
        'uses' => 'Auth\RegisterController@register'
    ]);
});

Route::group(['prefix' => 'auth', 'as' => 'session.'], function(){
    Route::get('login', [
        'as' => 'create',
        'uses'=>'Auth\LoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => 'store',
        'uses'=>'Auth\LoginController@login'
    ]);
    Route::get('logout', [
        'as' => 'destroy',
        'uses'=>'Auth\LoginController@logout'
    ]);
    Route::get('fb', [
        'as'=>'fb.login',
        'uses'=>'SocialAuthController@getFbAuth'
    ]);
    Route::get('fb/callback', [
        'as'=>'fb.callback',
        'uses'=>'SocialAuthController@getFbAuthCallback'
    ]);
});
Auth::routes();

Route::resource('realestates', 'RealEstatesController');
Route::resource('pricetags', 'PriceTagsController');

