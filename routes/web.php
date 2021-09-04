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

Route::get('/', function () {
    return redirect('event');
});

Route::namespace('App\Http\Controllers')->group(function(){
    Route::get('/event', 'EventController@index')->name('event.index');
    Route::get('/event/ajax', 'EventController@ajax')->name('event.ajax');
    Route::get('/event/add', 'EventController@add')->name('event.add');
    Route::post('/event/store', 'EventController@store')->name('event.store');
    Route::get('/event/edit/{id}', 'EventController@edit')->name('event.edit');
    Route::post('/event/update', 'EventController@update')->name('event.update');
    Route::get('/event/view/{id}', 'EventController@view')->name('event.view');
    Route::delete('/event/delete/{id}', 'EventController@delete')->name('event.delete');
});
