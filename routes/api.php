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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::resource('users', 'UserAPIController')->only('show', 'update')->middleware('auth:api');

Route::post('users', 'UserAPIController@store');
Route::post('users/device_token', 'UserAPIController@device_token');
Route::post('users/login', 'UserAPIController@login');
Route::post('users/verify_code', 'UserAPIController@verify_code');
Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('resetlink', 'PasswordResetController@sendResetLinkEmail');
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

Route::resource('users', 'UserAPIController');

Route::resource('branches', 'BranchAPIController');

Route::resource('departments', 'DepartmentAPIController');

Route::resource('appointments', 'AppointmentAPIController');

Route::get('roles/permissions', 'RoleAPIController@permissions');
Route::resource('roles', 'RoleAPIController');
