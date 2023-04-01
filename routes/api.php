<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;

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

Auth::routes();
Route::namespace('Admin')->group(function () {
    Route::post('account-deactivate', 'DashboardController@accountDeactivate');
    Route::get('cities', 'DashboardController@cities');
    Route::get('packages', 'PackageController@index');
    Route::get('rate-lists', 'RateListController@index');
    Route::get('projects', 'ProjectController@index');
    Route::get('featured-projects', 'ProjectController@isFeaturedProject');
    Route::post('add-project', 'ProjectController@addProject');
    Route::middleware('auth:api')->group(function () {
        Route::get('my-projects', 'ProjectController@myProject');
        Route::post('projects', 'ProjectController@apiStore');
        //   Route::post('add-project', 'ProjectController@addProject');
        Route::post('projects/{id}', 'ProjectController@apiUpdate');
        Route::post('upload-file', 'ProjectController@uploadProjectPdf');
        Route::post('products', 'ProductController@store');
        Route::post('products/{id}', 'ProductController@update');
        Route::post('profile-update', 'UserController@update');
    });
});
