<?php

use Illuminate\Http\Request;

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

// TODO: Find out why apiResource() can't be found
Route::middleware('auth:api')->resource('roles', 'RolesController');
Route::middleware('auth:api')->resource('users', 'UsersController');
