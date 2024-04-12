<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'App\Http\Controllers\Users', 'prefix' => 'users',], function () {
    Route::post('change_password', 'UserController@changePassword')->name('changePassword');
});

#upload
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('/upload', 'UploadController@upload')->name('upload');
    Route::post('/restore', 'UploadController@restore')->name('restore');
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/', 'CommentController@index')->name('index');
    });

    #settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::post('uploadmap', 'SettingController@uploadmap')->name('uploadmap');
    });

    #accounts
    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::delete('/{id}/destroy', 'AccountController@destroy')->name('destroy');
    });

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::post('/create', 'CommentController@store')->name('store');
    });

    #links
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('/getByType', 'LinkController@getByType')->name('getByType');
        Route::get('/getAll', 'LinkController@getAll')->name('getAll');
        Route::post('/create', 'LinkController@store')->name('store');
        Route::post('/update', 'LinkController@update')->name('update');
    });

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::post('/changeIsScan', 'LinkScanController@changeIsScan')->name('changeIsScan');
        Route::get('/getAll', 'LinkScanController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkScanController@destroy')->name('destroy');
    });

    #linkfollows
    Route::group(['prefix' => 'linkfollows', 'as' => 'linkfollows.'], function () {
        Route::get('/getAll', 'LinkFollowController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkFollowController@destroy')->name('destroy');
    });
});
