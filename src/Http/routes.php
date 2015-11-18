<?php

Route::get('easychat/list', [
    'uses'=> 'Ronanflavio\Easychat\Http\EasychatController@getIndex',
    'as' => 'easychat.index'
]);

// Route::group(['prefix' => config('packages.Ronanflavio.Easychat.config.uri'), 'before' => 'auth'], function()
Route::group(['prefix' => config('packages.Ronanflavio.Easychat.config.uri'), 'before' => 'auth'], function()
{
    Route::post('users/list', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@usersList', 'as' => 'easychat.users.list']);
    Route::post('send/message', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@sendMessage', 'as' => 'easychat.send.message']);
    Route::post('messages/list', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@messagesList', 'as' => 'easychat.messages.list']);
    Route::post('new/messages', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@newMessages', 'as' => 'easychat.new.messages']);
    Route::post('check/messages', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@checkMessages', 'as' => 'easychat.check.messages']);
    Route::post('check/allmessages', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@checkAllMessages', 'as' => 'easychat.check.allmessages']);
    Route::post('check/messages', ['uses' => 'Ronanflavio\Easychat\Http\EasychatController@checkMessages', 'as' => 'easychat.check.messages']);
    Route::controller('/', 'Ronanflavio\Easychat\Http\EasychatController');
});
