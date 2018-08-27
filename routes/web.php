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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/hunts', 'HuntController@index')->name('hunt.index');
    Route::get('/hunts/create', 'HuntController@create')->name('hunt.create');
    Route::post('/hunts/create', 'HuntController@store')->name('hunt.store');
    Route::delete('/hunts/{hunt}', 'HuntController@destroy')->name('hunt.delete');
    Route::get('/hunts/{hunt}', 'HuntController@show')->name('hunt.show');
    Route::post('/hunts/{hunt}/users/{user}', 'HuntController@addUser')->name('hunt.add_user');
    Route::delete('/hunts/{hunt}/users/{user}', 'HuntController@removeUser')->name('hunt.remove_user');

    Route::post('/hunts/{hunt}/goals', 'GoalController@store')->name('hunt.goal.store');
    Route::delete('/hunts/{hunt}/goals/{goal}', 'GoalController@destroy')->name('hunt.goal.delete');

    Route::post('/goals/{goal}/solutions', 'SolutionController@store')->name('solution.store');
    Route::patch('/goals/{goal}/solutions/{solution}', 'SolutionController@update')->name('solution.update');
});
