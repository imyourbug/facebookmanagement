<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/schedule', function () {
    return Artisan::call('schedule:run');
});

Route::get('/link', function () {
    return Artisan::call('storage:link');
});

Route::get('/optimize', function () {
    return Artisan::call('optimize:clear');
});

Route::get('/', function () {
    return view('user.login.index', [
        'title' => 'Đăng nhập'
    ]);
});

Route::get('/download/{filename}', function () {
    return view('user.login.index', [
        'title' => 'Đăng nhập'
    ]);
});

#customers
Route::group([
    'prefix' => 'customer', 'namespace' => 'App\Http\Controllers\Customers',
    'as' => 'customers.', 'middleware' => 'auth'
], function () {
    Route::get('me', 'CustomerController@me')->name('me');
    Route::post('me/update', 'CustomerController@update')->name('update');

    #contract
    Route::group(['prefix' => 'contracts', 'as' => 'contracts.'], function () {
        Route::get('/', 'ContractController@index')->name('index');
        Route::get('/detail/{id}', 'ContractController@detail')->name('detail');
        // Route::get('/{id}', 'contractController@show')->name('show');
    });

    #tasks
    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
        Route::get('/detail/{id}', 'TaskController@show')->name('detail');
    });

    #taskdetails
    Route::group(['prefix' => 'taskdetails', 'as' => 'taskdetails.'], function () {
        Route::get('/{id}', 'TaskDetailController@show')->name('show');
    });
});

#user
Route::group(['prefix' => 'user', 'namespace' => 'App\Http\Controllers\Users', 'as' => 'users.'], function () {
    Route::get('/', 'UserController@index')->name('home')->middleware('auth');
    Route::get('login', 'UserController@login')->name('login');
    Route::get('forgot', 'UserController@forgot')->name('forgot');
    Route::post('recover', 'UserController@recover')->name('recover');
    Route::post('login', 'UserController@checkLogin')->name('checkLogin');
    Route::get('register', 'UserController@register')->name('register');
    Route::post('register', 'UserController@checkRegister')->name('checkRegister');
    // Route::post('change_password', 'UserController@changePassword')->name('changePassword');
    Route::get('logout', 'UserController@logout')->name('logout');
    Route::get('me', 'UserController@me')->name('me');
    Route::post('me/update', 'UserController@update')->name('update');

    #task
    Route::group(['prefix' => 'tasks', 'as' => 'tasks.', 'middleware' => 'auth'], function () {
        Route::get('/', 'TaskController@index')->name('index');
        Route::get('/today', 'TaskController@taskToday')->name('taskToday');
        Route::get('/{id}', 'TaskController@show')->name('show');
    });

    #taskdetails
    Route::group(['prefix' => 'taskdetails', 'as' => 'taskdetails.', 'middleware' => 'auth'], function () {
        Route::get('/update/{id}', 'TaskDetailController@show')->name('show');
    });
});

#admin
Route::group([
    'prefix' => '/admin', 'namespace' => 'App\Http\Controllers\Admin',
    'as' => 'admin.', 'middleware' => 'admin'
], function () {
    Route::get('/', 'AdminController@index')->name('index');

    #plan
    Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
        Route::get('/', 'PlanController@index')->name('index');
    });

    #settings
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('backup', 'SettingController@backup')->name('backup');
        Route::get('reload', 'SettingController@reload')->name('reload');
    });

    #reports
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('/', 'ReportController@index')->name('index');
        Route::get('/reload/{id}', 'ReportController@reload')->name('reload');
        Route::get('/task/{id}', 'ReportController@task')->name('task');
        Route::get('/task/detail/{id}', 'ReportController@detail')->name('detail');
        Route::post('/duplicate', 'ReportController@duplicate')->name('duplicate');
    });

    #accounts
    Route::group(['prefix' => 'accounts', 'as' => 'accounts.'], function () {
        Route::get('/', 'AccountController@index')->name('index');
        Route::get('/create', 'AccountController@create')->name('create');
        Route::post('/create', 'AccountController@store')->name('store');
        Route::get('/update/{id}', 'AccountController@show')->name('show');
        Route::post('/update', 'AccountController@update')->name('update');
    });

    #customers
    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('/', 'CustomerController@index')->name('index');
        Route::get('/create', 'CustomerController@create')->name('create');
        Route::post('/create', 'CustomerController@store')->name('store');
        Route::get('/update/{id}', 'CustomerController@show')->name('show');
        Route::get('/detail/{id}', 'CustomerController@detail')->name('detail');
        Route::post('/update', 'CustomerController@update')->name('update');
    });

    #branches
    Route::group(['prefix' => 'branches', 'as' => 'branches.'], function () {
        Route::get('/', 'BranchController@index')->name('index');
        Route::get('/create', 'BranchController@create')->name('create');
        Route::post('/create', 'BranchController@store')->name('store');
        Route::get('/update/{id}', 'BranchController@show')->name('show');
        Route::post('/update', 'BranchController@update')->name('update');
    });

    #staffs
    Route::group(['prefix' => 'staffs', 'as' => 'staffs.'], function () {
        Route::get('/', 'InfoUserController@index')->name('index');
        Route::get('/create', 'InfoUserController@create')->name('create');
        Route::post('/create', 'InfoUserController@store')->name('store');
        Route::get('/update/{id}', 'InfoUserController@show')->name('show');
        Route::post('/update', 'InfoUserController@update')->name('update');
    });

    #types
    Route::group(['prefix' => 'types', 'as' => 'types.'], function () {
        Route::get('/', 'TypeController@index')->name('index');
        Route::get('/create', 'TypeController@create')->name('create');
        Route::post('/create', 'TypeController@store')->name('store');
        Route::get('/update/{id}', 'TypeController@show')->name('show');
        Route::post('/update', 'TypeController@update')->name('update');
    });

    #maps
    Route::group(['prefix' => 'maps', 'as' => 'maps.'], function () {
        Route::get('/', 'MapController@index')->name('index');
        Route::get('/create', 'MapController@create')->name('create');
        Route::post('/create', 'MapController@store')->name('store');
        Route::get('/update/{id}', 'MapController@show')->name('show');
        Route::post('/update', 'MapController@update')->name('update');
    });

    #chemistries
    Route::group(['prefix' => 'chemistries', 'as' => 'chemistries.'], function () {
        Route::get('/', 'ChemistryController@index')->name('index');
        Route::get('/create', 'ChemistryController@create')->name('create');
        Route::post('/create', 'ChemistryController@store')->name('store');
        Route::get('/update/{id}', 'ChemistryController@show')->name('show');
        Route::post('/update', 'ChemistryController@update')->name('update');
    });

    #solutions
    Route::group(['prefix' => 'solutions', 'as' => 'solutions.'], function () {
        Route::get('/', 'SolutionController@index')->name('index');
        Route::get('/create', 'SolutionController@create')->name('create');
        Route::post('/create', 'SolutionController@store')->name('store');
        Route::get('/update/{id}', 'SolutionController@show')->name('show');
        Route::post('/update', 'SolutionController@update')->name('update');
    });

    #items
    Route::group(['prefix' => 'items', 'as' => 'items.'], function () {
        Route::get('/', 'ItemController@index')->name('index');
        Route::get('/create', 'ItemController@create')->name('create');
        Route::post('/create', 'ItemController@store')->name('store');
        Route::get('/update/{id}', 'ItemController@show')->name('show');
        Route::post('/update', 'ItemController@update')->name('update');
    });

    #contracts
    Route::group(['prefix' => 'contracts', 'as' => 'contracts.'], function () {
        Route::get('/', 'ContractController@index')->name('index');
        Route::get('/create', 'ContractController@create')->name('create');
        Route::get('/update/{id}', 'ContractController@show')->name('show');
        Route::get('/detail/{id}', 'ContractController@detail')->name('detail');
        Route::post('/update', 'ContractController@update')->name('update');
    });

    #tasks
    Route::group(['prefix' => 'tasks', 'as' => 'tasks.'], function () {
        Route::get('/', 'TaskController@index')->name('index');
        Route::get('/create', 'TaskController@create')->name('create');
        Route::post('/create', 'TaskController@store')->name('store');
        // Route::get('/update/{id}', 'TaskController@show')->name('show');
        Route::get('/detail/{id}', 'TaskController@show')->name('detail');
        Route::post('/update', 'TaskController@update')->name('update');
    });

    #taskdetails
    Route::group(['prefix' => 'taskdetails', 'as' => 'taskdetails.'], function () {
        Route::get('/update/{id}', 'TaskDetailController@show')->name('show');
    });
});
