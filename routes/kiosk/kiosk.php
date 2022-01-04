<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 3/12/2018
 * Time: 1:10 PM
 */

Route::group(['prefix' => 'kiosk'], function (){
    Route::post('login','Kiosk\AuthController@login');
    Route::post('login-with-mac','Kiosk\AuthController@macLogin');

    Route::group(['middleware' => 'kiosk'],function (){
        Route::post('get-member' , 'MemberController@getMember');
        Route::post('get-updated-points' , 'MemberController@getUpdatedPoints');
        Route::post('get-kiosk-template' , 'OrganizationController@getKioskById');
        Route::post('get-all-kiosk-templates','Kiosk\KioskController@getAllKioskTemplates');
        Route::post('get-assigned-templates','Kiosk\KioskController@getAssignedTemplates');
    });
});