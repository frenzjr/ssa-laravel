<?php

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

Route::get('/', [
    'uses' => 'UsersController@index'
]);

Auth::routes();

Route::prefix('/users')->group(function () {
    Route::get('/', [
        'uses' => 'UsersController@index'
    ]);
    Route::get('index', [
        'uses' => 'UsersController@index'
    ]);
    Route::any('create', [
        'uses' => 'UsersController@create'
    ]);
    Route::any('edit/{id}', [
        'uses' => 'UsersController@edit'
    ]);
    Route::any('destroy/{id}', [
        'uses' => 'UsersController@destroy'
    ]);
    Route::any('show/{id}', [
        'uses' => 'UsersController@show'
    ]);
    Route::any('trashed/', [
        'uses' => 'UsersController@trashed'
    ]);
    Route::any('restore/{id}', [
        'uses' => 'UsersController@restore'
    ]);
    Route::any('delete/{id}', [
        'uses' => 'UsersController@delete'
    ]);
});
