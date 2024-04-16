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
Route::group(['namespace' => 'App\Http\Controllers\Users', 'prefix' => 'user',], function () {
    Route::post('change_password', 'UserController@changePassword')->name('changePassword');

    #linkscans
    Route::group(['prefix' => 'linkscans', 'as' => 'linkscans.'], function () {
        Route::post('/changeIsScan', 'LinkScanController@changeIsScan')->name('changeIsScan');
        Route::get('/getAll', 'LinkScanController@getAll')->name('getAll');
        Route::delete('/{id}/destroy', 'LinkScanController@destroy')->name('destroy');
    });

    #links
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('/getAll', 'LinkController@getAll')->name('getAll');
        Route::post('/update', 'LinkController@update')->name('update');
    });

    #comments
    Route::group(['prefix' => 'comments', 'as' => 'comments.'], function () {
        Route::get('/getAll', 'CommentController@getAll')->name('getAll');
    });

    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::delete('/{id}/destroy', 'ReactionController@destroy')->name('destroy');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/getAll', 'ReactionController@getAll')->name('getAll');
    });
});

#upload
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::post('/upload', 'UploadController@upload')->name('upload');
    Route::post('/restore', 'UploadController@restore')->name('restore');

    #linkHistories
    Route::group(['prefix' => 'linkHistories', 'as' => 'linkHistories.'], function () {
        Route::get('/getAll', 'LinkHistoryController@getAll')->name('getAll');
    });

    #links
    Route::group(['prefix' => 'links', 'as' => 'links.'], function () {
        Route::get('/getByType', 'LinkController@getByType')->name('getByType');
        Route::get('/getAll', 'LinkController@getAll')->name('getAll');
        // Route::post('/create', 'LinkController@store')->name('store');
        Route::post('/update', 'LinkController@update')->name('update');
        Route::post('/updateIsScanByLinkOrPostId', 'LinkController@updateIsScanByLinkOrPostId')->name('updateIsScanByLinkOrPostId');
        Route::post('/updateLinkByLinkOrPostId', 'LinkController@updateLinkByLinkOrPostId')->name('updateLinkByLinkOrPostId');
        Route::delete('/{id}/destroy', 'LinkController@destroy')->name('destroy');
    });
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function () {
    #reactions
    Route::group(['prefix' => 'reactions', 'as' => 'reactions.'], function () {
        Route::get('/', 'ReactionController@index')->name('index');
        Route::delete('/{id}/destroy', 'ReactionController@destroy')->name('destroy');
        Route::post('/create', 'ReactionController@store')->name('store');
        Route::get('/getAll', 'ReactionController@getAll')->name('getAll');
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
        Route::get('/', 'CommentController@index')->name('index');
        Route::delete('/{id}/destroy', 'CommentController@destroy')->name('destroy');
        Route::post('/create', 'CommentController@store')->name('store');
        Route::get('/getAll', 'CommentController@getAll')->name('getAll');
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
