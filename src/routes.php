<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(array('prefix' => Config::get('easychat::uri'), 'before' => 'auth'), function()
{
    Route::controller('/', 'Ronanflavio\Easychat\Controllers\ChatController');
});
