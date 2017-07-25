<?php

Route::get('assets/app.js', 'AssetsController@javascript')->name('js');
Route::get('assets/app.css', 'AssetsController@css')->name('css');
Route::get('images/{image}', 'AssetsController@images')->name('image');

Route::get('/', 'StageController@index')->name('index');

Route::group(['prefix' => 'column'], function() {
    Route::get('/{table}', 'ColumnController@show')->name('show');
    Route::put('/{table}', 'ColumnController@update')->name('update');
});

Route::group(['prefix' => 'list'], function() {
    
    Route::put('update/{table}/{group}', 'ListController@update');
    Route::delete('destroy/{table}/{group}/{column}', 'ListController@destroy');
    
    Route::delete('destroyGroup/{table}/{group}', 'ListController@destroyGroup');
    Route::post('createGroup/{table}', 'ListController@createGroup');
});