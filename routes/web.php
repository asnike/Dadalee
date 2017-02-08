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


Route::group(['prefix'=>'realestates', 'as'=>'realestate.'], function(){
    Route::get('tradeprice', [
        'as'=>'trade.price',
        'uses'=>'RealEstatesController@tradePrice'
    ]);
    Route::put('{id}/earning', [
        'as'=>'earning.update',
        'uses'=>'RealEstatesController@earning'
    ]);
    Route::put('{id}/loan', [
        'as'=>'loan.update',
        'uses'=>'RealEstatesController@loan'
    ]);
    Route::put('{id}/tenant', [
        'as'=>'tenant.update',
        'uses'=>'RealEstatesController@tenant'
    ]);
});
Route::resource('realestates', 'RealEstatesController');
Route::resource('pricetags', 'PriceTagsController');
Route::resource('actualprices', 'ActualPricesController');

Route::get('actualprices/contain/{latlng}', [
    'as'=>'contain',
    'uses'=>'ActualPricesController@contain'
]);

Route::get('repaymethods',[
    'as'=>'repay.methods',
    'uses'=>'RepayMethodsController@index',
]);

Route::get('tradeprice', [
    'as'=>'trade.price',
    'uses'=>'RealEstatesController@tradePrice'
]);


/*admin*/
Route::group(['prefix' => 'admin/auth', 'as' => 'admin.session.'], function(){
    Route::get('login', [
        'as' => 'create',
        'uses'=>'Auth\AdminLoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => 'store',
        'uses'=>'Auth\AdminLoginController@login'
    ]);
    Route::get('logout', [
        'as' => 'destroy',
        'uses'=>'Auth\AdminLoginController@logout'
    ]);
});

Route::group(['prefix'=>'admin', 'as'=>'admin.','middleware'=>['admin']], function() {
    Route::get('dashboard', [
       'as'=>'dashboard',
        'uses'=>'Admin\DashboardsController@index'
    ]);
    Route::resource('users', 'Admin\UsersController');
    Route::resource('prices', 'Admin\ActualPricesController');
    Route::post('prices/geocoding', [
        'as'=>'geocoding',
        'uses'=>'Admin\ActualPricesController@geocoding'
    ]);
});