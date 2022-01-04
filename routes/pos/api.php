<?php
Route::group(['prefix' => 'pos'] , function(){
    Route::post('login','PosController@login');

    Route::group(['middleware' => 'kiosk'],function(){
        Route::post('update-member-points','PosController@updateMemberPoints');
    });
});