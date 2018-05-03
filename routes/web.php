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

Route::get('/', ['middleware' => 'guest',function () {
    return view('pages.index');
}]);

//Login & Logout 
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

//Dashboard
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/events', 'DashboardController@returnEvents');
Route::get('/dashboard/maintenanceRating', 'DashboardController@maintenanceRating');
Route::get('/dashboard/bar', 'DashboardController@barChart');
Route::get('/todo', 'UserTasksController@index');

Route::resource('todo', 'UserTasksController');

Route::get('/serviceHistory/pdf', 'ServiceHistoryController@viewPdf')->name('viewPDF');
Route::resource('serviceHistory', 'ServiceHistoryController');
Route::resource('fuel', 'FuelController');
Route::resource('expenses', 'ExpenditureController');

Route::post('/dashboard/{id}/updateMileage', 'DashboardController@updateMileage');
