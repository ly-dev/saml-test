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
    'prefix' => 'auth',
    'middleware' => [
        'web'
    ]
], function () {
    // Authentication Routes...
    Route::get('login', 'AuthController@showLoginForm');
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');

    Route::post('register', 'AuthController@register');

    Route::get('verify-email/{token}', 'AuthController@verifyEmail');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'PasswordController@showResetForm');
    Route::post('password/email', 'PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'PasswordController@reset');

    Route::get('user', 'UserController@index');
    Route::get('user/grid', 'UserController@grid');
    Route::get('user/view/{id}', 'UserController@view');
    Route::post('user/process', 'UserController@process');

    // change own password
    Route::get('password/change', 'SelfController@changePassword');
    Route::post('password/save', 'SelfController@savePassword');
    Route::get('email-verification', 'SelfController@showEmailVerificationForm');
    Route::post('email-verification', 'SelfController@processEmailVerification');
    Route::get('resend-email-verification', 'SelfController@resendEmailVerification');
});

// facebook
Route::group([
    'prefix' => 'facebook',
    'middleware' => [
        'web'
    ]
], function () {
    Route::get('login', 'FacebookController@login');
    Route::get('login-callback', 'FacebookController@loginCallback');
    Route::get('login-result', 'FacebookController@loginResult');
});
