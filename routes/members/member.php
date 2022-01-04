<?php
Route::group(['prefix' => 'members'] , function(){
    Route::post('login','MemberController@login');
    Route::post('sign-up','MemberController@signUp');
    Route::post('verify-member-email','MemberController@verifyMemberEmail');
    Route::get('verify/{token}','MemberController@verifyMemberAccount');
    Route::post('send-reset-email', 'MemberController@sendResetEmail');

    Route::group(['middleware' => 'member-login'],function(){
       Route::post('organization-list' , 'MemberController@organiztionList');
       Route::get('get-member-cards' , 'MemberController@getMemberWithTemplates');
       Route::get('get-rewards-list' , 'MemberController@getRewardList');
       Route::get('get-coffee-card-list' , 'MemberController@getMemberCoffeeCardList');
       Route::post('get-coffee-card-log','MemberController@getCoffeeCardLogs');
       Route::get('get-member-vouchers' , 'MemberController@getMemberVouchers');
       Route::post('update-personal-profile','MemberController@updatePersonalProfile');
       Route::post('update-password','MemberController@updatePassword');
       Route::post('virtual-card-assign','MemberController@assignCoffeeCard');
       Route::post('delete-member-coffee-card','MemberController@deleteMemberCoffeeCard');
    });
});