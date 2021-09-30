<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes Module MyCourse
Route::post('transaction', 'TransactionController@submit'); // - API Submit
Route::get('transaction', 'TransactionController@get_data'); // - API Get
Route::get('transaction/{id}', 'TransactionController@get_data'); // - API Get by ID
Route::delete('transaction/{id}', 'TransaactionController@delete'); // - API Delete by ID
Route::put('transaction/{id}', 'TransactionController@update'); // - API PUT by ID
Route::patch('transaction/{id}', 'TransactionController@update'); // - API PATCH by ID
