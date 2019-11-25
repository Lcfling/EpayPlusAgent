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
//验证码
Route::get('/verify',                   'Admin\HomeController@verify');
//登陆模块
Route::group(['namespace'  => "Auth"], function () {
    Route::get('/login',                'LoginController@showLoginForm')->name('login');
    Route::post('/login',               'LoginController@login');
    Route::get('/logout',               'LoginController@logout')->name('logout');
    Route::get('/register',             'BindController@index');
    Route::post('/valAccount',          'BindController@checkAccount');//效验账号是否存在
    Route::post('/valUser',             'BindController@checkUserLogin');//效验账号密码的真实性
    Route::post('/sendSMS',             'BindController@sendSMS');//发送验证码
    Route::post('/bindCode',            'BindController@bindCode');//绑定加效验
});
//后台主要模块
Route::group(['namespace' => "Agent",'middleware' => ['auth']], function () {
    Route::get('/',                     'HomeController@index');
    Route::get('/index',                'HomeController@welcome');
    Route::resource('/menus',           'MenuController');
    Route::get('/info/userinfo','InfoController@userinfo');
    Route::post('/info/resInfo','InfoController@updateInfo');
    Route::post('/info/valPwd','InfoController@valPwd');
    Route::post('/info/resPwd','InfoController@resPwd');
    Route::post('/info/valPaypwd','InfoController@valPaypwd');
    Route::post('/info/resPaypwd','InfoController@resPaypwd');
    Route::post('/info/setPayPwd','InfoController@setPayPwd');
    Route::resource('/info','InfoController');
    Route::resource('/bank','BankController');
    Route::resource('/draw','DrawController');
    Route::resource('/billflow','BillflowController');
});