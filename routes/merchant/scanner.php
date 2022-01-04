<?php
Route::group(['prefix' => 'scanner'], function(){
   Route::post('login','ScannerController@login');
   Route::group(['middleware' => 'merchant'], function(){
       Route::post('get-card-details','ScannerController@getCardDetails');
       Route::post('add-stamp-on-member-coffee-card','ScannerController@addStamp');
       Route::post('redeem-member-reward','ScannerController@redeemMemberReward');
   });
});