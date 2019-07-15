<?php
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Module Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for the module.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the Closure to execute when that URI is requested.
 * |
 */
Route::group(
    [
        'prefix' => 'basicpage',
        'middleware' => [
            'web'
        ]
    ],
    function () {
        Route::get('/', 'BasicPageController@index');
        Route::get('/grid', 'BasicPageController@grid');
        Route::get('/create', 'BasicPageController@create');
        Route::get('/view/{id}', 'BasicPageController@view');
        Route::post('/process', 'BasicPageController@process');
        Route::delete('/delete/{id}', 'BasicPageController@delete');
    });

Route::group(
    [
        'prefix' => 'page',
        'middleware' => [
            'web'
        ]
    ],
    function () {
        Route::get('/{slug}', 'BasicPageController@show');
    });

// API Route
Route::group(
    [
        'prefix' => 'api/v1',
        'middleware' => [
            'api'
        ]
    ],
    function () {
        Route::get('/basic-page/load/{slug}', 'BasicPageApiController@load');
    });

