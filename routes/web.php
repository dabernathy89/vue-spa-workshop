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
    Route::get('/api/ownedHunts', 'HuntController@ownedHunts')->name('hunt.owned');
    Route::get('/api/otherHunts', 'HuntController@otherHunts')->name('hunt.other');
    Route::get('/api/hunts/{hunt}', 'HuntController@show')->name('hunt.show');
    Route::get('/hunts', 'HuntController@index')->name('hunt.index');
    Route::get('/hunts/create', 'HomeController@index')->name('hunt.create');
    Route::post('/hunts', 'HuntController@store')->name('hunt.store');
    Route::patch('/hunts/{hunt}', 'HuntController@update')->name('hunt.update');
    Route::delete('/hunts/{hunt}', 'HuntController@destroy')->name('hunt.delete');
    Route::get('/hunts/{hunt}', 'HomeController@index')->name('hunt.show');
    Route::get('/hunts/{hunt}/solutions', 'HuntController@showSolutions')->name('hunt.show.solutions');
    Route::post('/hunts/{hunt}/users/{user}', 'HuntController@addUser')->name('hunt.add_user');
    Route::delete('/hunts/{hunt}/users/{user}', 'HuntController@removeUser')->name('hunt.remove_user');

    Route::post('/hunts/{hunt}/goals', 'GoalController@store')->name('hunt.goal.store');
    Route::delete('/hunts/{hunt}/goals/{goal}', 'GoalController@destroy')->name('hunt.goal.delete');

    Route::post('/goals/{goal}/solutions', 'SolutionController@store')->name('solution.store');
    Route::patch('/goals/{goal}/solutions/{solution}', 'SolutionController@update')->name('solution.update');
});
