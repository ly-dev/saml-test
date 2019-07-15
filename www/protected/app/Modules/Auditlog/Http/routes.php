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
    'prefix' => 'audit-log',
    'middleware' => [
        'web'
    ]
], function () {
    Route::get('/', 'AuditlogController@index');
    Route::get('/grid', 'AuditlogController@grid');
});
