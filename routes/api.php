<?php

use Illuminate\Support\Facades\Route;

#--------------------------------##   <editor-fold desc="Admin Authenticate Routes">   ##--------------------------------------------#
Route::group(['namespace' => 'Authenticate'], function () {

    Route::post('login', 'LoginController@login')->name('user.login');
    Route::post('logout', 'LoginController@logout')->name('user.logout');
    Route::get('user', 'UserController@index')->name('user');

});
# </editor-fold>


Route::apiResource('webinars' , 'WebinarsController');
Route::apiResource('providers' , 'ProvidersController');