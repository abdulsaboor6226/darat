<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();


Route::namespace('Admin')->middleware(['admin'])->group(function () {

    Route::get('dashboard', 'DashboardController@index');
    Route::resource('packages', 'PackageController');
    Route::resource('projects', 'ProjectController');
    Route::post('is-featured/{id}', 'ProjectController@isFeatured');
    Route::post('is-approved/{id}', 'ProjectController@isApproved');
    Route::get('modrate-projects', 'ProjectController@modrateProject');
    Route::resource('products', 'ProductController');
    Route::resource('rate-lists', 'RateListController');
    Route::resource('users', 'UserController');
});

//   FailBack route
Route::fallback(function () {
    return view('errors.404-errors');
});
Route::get('migrate-fresh', function () {
    \Artisan::call('migrate:fresh');
    dd("Migration Freshed");
});
Route::get('migrate', function () {
    \Artisan::call('migrate');
    dd("Migration Completed");
});
Route::get('column-add', function () {
    \Artisan::call('migrate --path=database/migrations/2022_01_20_202038_add_column_mobile_os_to_users_table.php');
    dd("Migration Completed");
});

Route::get('column-add-to-project', function () {
    \Artisan::call('migrate --path=database/migrations/2022_01_24_200731_add_column_pdf_file_to_projects_table.php');
    dd("Migration Completed");
});
Route::get('column-add-to-project-2', function () {
    \Artisan::call('migrate --path=database/migrations/2022_03_14_080444_add_column_featured_phone_number_to_projects_table.php');
    dd("Migration Completed");
});
Route::get('column-add-to-user-3', function () {
    \Artisan::call('migrate --path=database/migrations/2022_03_15_132304_add_column_device_to_users_table.php');
    dd("Migration Completed");
});
Route::get('seed', function () {
    \Artisan::call('db:seed');
    dd("Seeding Completed");
});
Route::get('passport-install', function () {
    \Artisan::call('passport:install');
    dd("install");
});
Route::get('clear-cache', function () {
    \Artisan::call('optimize:clear');
    dd("Clear");
});
