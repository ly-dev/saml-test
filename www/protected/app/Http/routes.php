<?php
use Illuminate\Support\Facades\Route;

//@TODO revise below solution from
// https://stackoverflow.com/questions/34748981/laravel-5-2-cors-get-not-working-with-preflight-options
header('Access-Control-Allow-Origin: *');
header( "Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
header( "Access-Control-Allow-Methods: POST,GET,OPTIONS,DELETE,PUT,PATCH");

/*
 * |--------------------------------------------------------------------------
 * | Routes File
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you will register all of the routes in an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 */

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | This route group applies the "web" middleware group to every route
 * | it contains. The "web" middleware group is defined in your HTTP
 * | kernel and includes session state, CSRF protection, and more.
 * |
 */

Route::group([
    'middleware' => [
        'web'
    ]
], function () {
    Route::get('/', 'HomeController@index');
});

// API Route
Route::group([
    'prefix' => 'api/v1',
    'middleware' => [
        'api'
    ]
], function () {
    Route::post('/sync/app', 'AppApiController@syncApp');
});
