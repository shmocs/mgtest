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
    return view('welcome');
});


Route::get('/movie/{id}', 'MovieController@index');
Route::get('/movies', 'MovieController@showAll');
Route::post('/store-movie', 'MovieController@storeMovie');

Route::post('/store-image', 'ImageController@storeImage');


Route::get('/{id}', function (\Illuminate\Http\Request $request, $id) {
    return \App\Movie::findOrFail($id);
});
