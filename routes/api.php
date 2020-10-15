<?php

use App\Http\Controllers\Admin\ProductController;
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

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

// Common routes
Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::get('user', 'AuthController@user');
    Route::put('users/info', 'AuthController@updateInfo');
    Route::put('users/password', 'AuthController@updatePassword');
});

//  Admin routes
Route::group([
    'middleware' => ['auth:api', 'scope:admin'],
    'prefix' => 'admin',
    'namespace' => 'Admin'
], function () {

    Route::get('chart', 'DashboardController@chart');
    Route::post('upload', 'ImageController@upload');
    Route::get('export', 'OrderController@export');

    Route::apiResource('users', 'UserController');
    Route::apiResource('roles', 'RoleController');
    Route::apiResource('products', 'ProductController');
    Route::apiResource('orders', 'OrderController')->only('index', 'show');
    Route::apiResource('permissions', 'PermissionController')->only('index');
});

// Influencer routes
Route::group([
    'prefix' => 'influencer',
    'namespace' => 'Influencer'
], function () {
    Route::get('products', 'ProductController@index');
});
