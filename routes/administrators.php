<?php

use Illuminate\Support\Facades\Route;
use MaxDev\Enums\ContentTypes;

Route::group([
    'middleware' => 'administrator',
    'namespace' => '\MaxDev\Modules\Administrators',
    'prefix' => 'administrators',
    'as' => 'admin.'
], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::get('logout', 'AdminLoginController@logout')->name('logout');
        Route::get('login', 'AdminLoginController@ShowLoginForm')->name('login');
        Route::post('login', 'AdminLoginController@login')->name('login.submit');
    });

    /*
    Route::group(['prefix'=>'password'],function(){
        Route::post('email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('reset', 'Auth\AdminResetPasswordController@reset')->name('password.reset.submit');
        Route::get('reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('password.reset');
    });
    */

    Route::group(['middleware' => ['auth:admin']], function () {

        /**
         * Json needed routes for administrators
         */
        /*
        * Dashboard
        */

        Route::resource('admins', 'AdminsController', ['parameters' => ['admins' => 'user']]);
        Route::resource('admin-groups', 'AdminGroupsController');
        Route::resource('logs', 'LogsController', ['only' => ['index', 'show']]);
        Route::resource('settings', 'SettingController');
        Route::get('', 'DashboardController@index')->name('dashboard');
        Route::get('no-access', 'DashboardController@NoAccess')->name('no-access');
        Route::MATCH(['GET', 'PATCH'], 'profile', 'DashboardController@setting')->name('profile');
		Route::resource('/emailmodel', 'EmailmodelController');
		Route::resource('/contact', 'ContactController');

        Route::get('upload/{id}', 'EventController@upload')->name('event.upload');
        Route::post('import', 'EventController@import')->name('event.import');
		Route::resource('/event', 'EventController');
		Route::resource('/contact', 'ContactController');
		Route::resource('/contactemail', 'ContactemailController');


        Route::get('send/mail/{id}', 'EmailmodelController@sendMails')->name('emailmodel.send.mail');

        
    });
});
