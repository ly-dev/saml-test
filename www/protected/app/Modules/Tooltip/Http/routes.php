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
Route::group([
    'prefix' => 'tooltip',
    'middleware' => [
        'web'
    ]
], function () {
    Route::get('/', 'TooltipController@index');
    Route::get('/grid', 'TooltipController@grid');
    Route::get('/create', 'TooltipController@create');
    Route::get('/view/{page_id}/{tooltip_id}', 'TooltipController@view');
    Route::post('/process', 'TooltipController@process');
    Route::delete('/delete/{page_id}/{tooltip_id}', 'TooltipController@delete');
});

// Ajax Route
Route::group([
    'prefix' => 'ajax/tooltip',
    'middleware' => [
        'throttle:600,1' // throttle
    ]
], function () {
    Route::get('/{page_id}/{tooltip_id}', 'TooltipController@ajaxView');
});