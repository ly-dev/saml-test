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
    'prefix' => 'taxonomy',
    'middleware' => [
        'web'
    ]
], function () {
    Route::get('/{term}', 'TaxonomyController@index');
    Route::get('/grid/{term}', 'TaxonomyController@grid');
    Route::get('/view/{term}/{id}', 'TaxonomyController@view');
    Route::post('/process/{term}', 'TaxonomyController@process');
    Route::delete('/delete/{term}/{id}', 'TaxonomyController@delete');
});
