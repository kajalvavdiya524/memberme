<?php


use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
require_once __DIR__.'/kiosk/kiosk.php'; //kiosk application routes
require_once __DIR__.'/members/member.php'; //member application routes
require_once __DIR__.'/merchant/scanner.php';    //scanner routes
require_once __DIR__.'/pos/api.php';

if(!headers_sent()){
    header('Access-Control-Allow-Origin: *');
    header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );
}

Route::post('auth/refresh','UserController@refreshToken');
Route::post('webhook-response',function(Request $request){
    Log::info($request->all());
});
Route::get('sms-reply-webhook',function(Request $request){
    Log::info($request->all());
});
Route::get('sms-status-webhook',function(Request $request){
    Log::info($request->all());
});
Route::get('stripe',function(Request $request){
    $plans = \Cartalyst\Stripe\Laravel\Facades\Stripe::invoices()->all([
        'customer' => 'cus_E6xt2cjeyB0Btp'
    ]);
    dd($plans);

});
Route::options('login', ['uses' => 'Auth\LoginController@apiLogin', 'as' => 'api.login' ])->middleware('cors');
Route::post('login', ['uses' => 'Auth\LoginController@apiLogin', 'as' => 'api.login' ])->middleware('cors');
Route::post('register', ['uses' => 'Auth\RegisterController@apiRegister'])->middleware('cors');
Route::post('get-card-info',['uses' => 'Auth\RegisterController@getCardInfo']);
Route::post('profile', [
    'uses' => 'UserController@saveApiDetails',
    'as' => 'saveUserApiDetails',
    'middleware' => 'user-auth-jwt',
]);
Route::post('organization',[
    'uses' => 'OrganizationController@saveApiDetails',
    'as' => 'saveOrgApiDetails',
    'middleware' => 'user-auth-jwt',
]);
Route::post('savingDetails',[
    'uses' => 'UserController@saveBulkDetails',
    'as' => 'save.bulk.details',
]);
Route::post('sendresetemail',[
    'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail',
    'as' => 'sendEmail',
]);
Route::post('send-reset-email','UserController@sendResetEmail');
Route::post('password/reset/','Auth\ResetPasswordController@reset');
Route::post('authenticate-token','UserController@authenticateToken');
Route::post('save-bulk-details','UserController@saveBulkDetails');
Route::get('records/get-industry-list','RecordController@getAllIndustries');
Route::get('get-ethnicity-list', 'DropdownController@getEthnicityList');
Route::get('records/get-pos-member-list','RecordController@getAllPosMembers');
Route::get('records/member-title-list','DropdownController@getMemberTitleList');
Route::get('records/member-status-list','DropdownController@getMemberStatusList');
Route::get('records/get-time-zone-list/{name?}','DropdownController@getTimeZoneList');
//Route::match(['post', 'options'], 'api/register', 'Auth\RegisterController@apiRegister')->middleware('cors');
Route::group(['middleware' => 'user-auth-jwt'],function(){
    Route::group(['prefix' => 'org'], function(){
        //auth api on organization deals
        Route::post('get-org-details','OrganizationController@getList');
        Route::post('set-current-org','OrganizationController@setCurrentOrganization');
        Route::post('get-current-org','OrganizationController@getCurrentOrganization');
    });

    Route::group(['prefix' => 'dashboard'],function(){
        Route::post('get-all-details','DashboardController@getAllDetails');
        Route::get('get-birthday_members','DashboardController@getCurrentMothBirthdayMembers');
    });
});
/**
 * All routes for admin.
 */
Route::group(['prefix' => 'admin'], function(){
    //adding Security for super admin.
    Route::group(['middleware' => ['admin','user-auth-jwt']],function(){
        Route::get('get-all-organizations','AdminController@getAllOrg');
        Route::get('is-admin','AdminController@isAdmin');
        Route::get('sync-plans','StripeController@syncPlans');

        Route::get('get-kiosk-auth/{id}','Kiosk\AuthController@getPassword');

        Route::get('get-kiosk-list','Kiosk\KioskController@getList');
        Route::post('set-kiosk-organization','Kiosk\KioskController@setKioskOrganization');
        Route::post('update-kiosk','Kiosk\KioskController@update');
        Route::post('delete-kiosk','Kiosk\KioskController@delete');
        Route::post('kiosk-background/create','Kiosk\BackgroundController@create');
        Route::post('import/member/csv','AdminController@importMembers');

        Route::post('push-members-to-pos', 'AdminController@pushMembersToPos');

        Route::group(['prefix' => 'coffee-card'],function(){
            Route::post('/create', 'CoffeeCardController@store');
            Route::get('/all-assigned-organizations','CoffeeCardController@getAllAssignedOrganization');
            Route::post('/assign-organization', 'CoffeeCardController@assignOrganization');
            Route::post('/detach-organization', 'CoffeeCardController@detachOrganization');
            Route::post('/get-all-coffee-cards', 'CoffeeCardController@adminCoffeeCardList');
        });

        Route::group(['prefix' => 'office-use'],function (){
            Route::post('update', 'OrganizationController@updateOfficeUse');
        });
        /* Records admin Routes */
        Route::group(['prefix' => 'records'],function(){

            Route::post('create-industry','RecordController@createIndustry');
            Route::post('edit-industry','RecordController@editIndustry');
            Route::post('delete-industry','RecordController@deleteIndustry');

            Route::post('create-pos-member','RecordController@createPosMember');
            Route::post('edit-pos-member','RecordController@editPosMember');
            Route::post('delete-pos-member','RecordController@deletePosMember');
        });
        /* End Records admin Routes */
    });

    Route::group(['middleware' => 'authorized_persons'],function(){

        Route::post('add-log',function(Request $request){
            $message = $request->get('message');
            Log::info('Frontend: ' . $message);
        });
        Route::post('logout','UserController@apiLogout');
        Route::group(['prefix' => 'users'],function(){
            Route::post('add','UserController@addUser');
            Route::post('update','UserController@updateUser');
            Route::post('get-list','UserController@getList');
            Route::post('change-password','UserController@updatePassword');
            Route::post('remove-user-from-organization','UserController@unlinkUser');
            Route::post('re-login','UserController@reApiLogin');
        });

        Route::group(['prefix' => 'org'],function(){
            Route::get('get-office-use', 'OrganizationController@getOfficeUse');
            Route::post('get-org','OrganizationController@getById');
            Route::post('save-open-field', 'OrganizationController@saveField');
            Route::post('option-update', 'OrganizationController@updateOptionField');
            Route::post('upload-avatar','OrganizationController@uploadLogo');
            Route::post('update-name','OrganizationController@changeName');
            Route::post('update-postal-address','OrganizationController@updatePostalAddress');
            Route::post('update-physical-address','OrganizationController@updatePhysicalAddress');
            Route::post('set-time-zone','OrganizationController@setTimeZone');
            Route::post('add-payment-type','OrganizationController@addPaymentType');
            Route::post('get-payment-types','OrganizationController@getPaymentTypes');
            Route::post('get-payment-types-for-dropdown','DropdownController@getPaymentTypesDropDown');
            Route::post('get-payment-types-for-part-pay','DropdownController@getPaymentTypesForPartPay');
            Route::post('set-sms-fields','OrganizationController@insertSmsDetails');
            Route::post('send-sms-to-group','OrganizationController@sendSmsToGroup');
            Route::post('get-sms-balance','OrganizationController@getSmsBalance');
            Route::post('get-sms-cost','OrganizationController@getSmsCost');
            Route::post('save-kiosk-template','OrganizationController@saveKioskTemplate');
            Route::post('get-kiosk-password','OrganizationController@getKioskPassword');
            Route::post('get-kiosk','OrganizationController@getKioskById');
            Route::post('get-kiosk-template-list','OrganizationController@getAllKioskTemplate');
            Route::post('update-organization-settings','OrganizationController@updateSettings');
            Route::post('update-sendgrid-key','OrganizationController@updateSendgridKey');
            Route::post('verify-sendgrid-template-id', 'EmailTemplateController@verifyTemplateId');
            Route::post('get-email-template-list','OrganizationController@getAllEmailTemplates');
            Route::get('get-sendgrid-api','OrganizationController@getSendgridApi');
            Route::get('get-untill-setting','OrganizationController@getUntillSetting');
            Route::post('update-untill-setting','OrganizationController@updateUntillSetting');
            Route::post('update-email-templates','OrganizationController@updateEmailTemplates');
            Route::get('get-all-notification','OrganizationController@getAllNotifications');
            Route::post('update-notification-field', 'OrganizationController@updateNotificationField');
            Route::get('seen-all-notification','OrganizationController@markNotificationAsSeen');
            Route::get('get-voucher-parameter-settings','OrganizationController@getKioskVoucherParameterSettings');
            Route::post('click-a-notification','OrganizationController@markNotificationAsClicked');
            Route::post('assign-groups-to-email-templates','OrganizationController@assignEmailTemplatesToGroups');
            Route::post('assign-subscriptions-to-email-templates','OrganizationController@assignEmailTemplatesToSubscriptions');
            Route::get('get-untill-client-names', 'RecordController@getUntillClientNames');
            Route::post('push-save-points-to-pos', 'OrganizationController@pushSavePointsToPos');
            Route::post('push-empty-save-points-to-pos', 'OrganizationController@pushEmptySavePointsToPos');
            Route::group(['prefix' => 'group'], function(){
                Route::post('create','GroupController@create');
                Route::post('list','GroupController@getList');
                Route::post('list-all','GroupController@getAllGroups');
                Route::post('dropdown-list-all','GroupController@dropdownList');
                Route::post('update','GroupController@update');
                Route::post('delete','GroupController@delete');
            });
        });

        Route::group(['prefix' => 'kiosk'],function(){
            Route::post('add-voucher-parameter','Kiosk\KioskController@addVoucherParameter');
        });

        Route::group(['prefix' => 'contacts'], function(){
            Route::post('add','ContactController@addContact');
            Route::post('get-list/{orgId?}','ContactController@getList');
            Route::post('convert-to-member','ContactController@convertToMember');
        });

        Route::group(['prefix' => 'members'],function(){
            Route::post('add','MemberController@addMember');
            Route::post('change','MemberController@changeField');
            Route::delete('delete/{id}','MemberController@delete');
            Route::post('get-details' , 'MemberController@getMemberDetails');
            Route::post('get-sent-messages' , 'MemberController@getSentMessages');
            Route::post('get-member' , 'MemberController@getMember');
            Route::post('update-address/{type}','MemberController@updateAddress');
            Route::post('get-list/{orgId?}','MemberController@getList');
            Route::post('get-lookup-list','MemberController@getMemberLookupList');
            Route::post('save-group','MemberController@saveGroup');
            Route::post('save-interest','MemberController@createInterest');
            Route::post('delete-interest','MemberController@deleteInterest');
            Route::post('get-interest-list','MemberController@getInterests');
            Route::post('set-template','MemberController@setTemplate');
            Route::post('add-subscription','MemberController@addSubscription');
            Route::post('upload-identity','MemberController@uploadIdentity');
            Route::post('get-authorised_fields','MemberController@searchField');
            Route::post('get-member-transactions','MemberController@getMemberTransactionList');
            Route::post('update-payment-info','MemberController@updateMemberPaymentInfo');
            Route::get('get-payment-info/{memberId}','MemberController@getMemberPaymentInfo');
            Route::post('subscription-expired-members', 'MemberController@getSubscriptionExpiredMembers');
            Route::post('re-generate-member-card', 'MemberController@reGenerateMemberCard');
            Route::group(['prefix' => 'others'],function (){
                Route::post('change','MemberController@changeOtherField');
            });
            Route::group(['prefix' => 'employment'],function (){
                Route::post('get-all','MemberController@getAllEmployments');
                Route::post('create','MemberController@createEmployment');
                Route::post('delete','MemberController@removeEmployment');
            });
            Route::group(['prefix' => 'education'],function (){
                Route::post('get-all','MemberController@getAllEducations');
                Route::post('create','MemberController@createEducation');
                Route::post('delete','MemberController@removeEducation');
            });
            Route::get('verify-member-id/{id}' , 'MemberController@verifyMemberId');
            Route::get('get-next-member-no','MemberController@getNextMemberNumber');
            Route::get('get-pos-card-names','MemberController@getMemberCardNames');

            Route::get('get-view-logs/{id}','MemberController@getMemberViewLogs');
            Route::post('add-view-log','MemberController@addViewLog');

            Route::get('get-change-logs/{id}','MemberController@getMemberChangeLogs');
        });

        Route::group(['prefix' => 'payments'],function(){
           Route::post('add','PaymentController@create');
           Route::post('update','PaymentController@update');
           Route::post('delete','PaymentController@delete');
           Route::post('archive-all','PaymentController@archiveAllPayments');
           Route::post('member-payment-list','PaymentController@getMemberPaymentList');
        });

        Route::group(['prefix' => 'transaction'],function (){
            Route::post('/create','PaymentController@createTransaction');
            Route::post('/create-part-payment','PaymentController@createPartPayment');
            Route::post('/get-all','TransactionController@getAllTransactions');
        });

        Route::group(['prefix' => 'receipt'],function (){
            Route::post('/send-email','TransactionController@sendEmail');
        });

        Route::group(['prefix' => 'receipt'],function(){
//            Route::post('/send-email','TransactionController@');
        });


        Route::group(['prefix' => 'subscription'],function(){
            Route::post('get-list','SubscriptionController@index');
            Route::post('get-subscription-dropdown-list','DropdownController@getSubscriptions');
            Route::post('create','SubscriptionController@store');
            Route::post('update','SubscriptionController@update');
            Route::post('get-subscription/{id}','SubscriptionController@show');
            Route::post('change/{id}','SubscriptionController@changeField');
            Route::delete('delete/{id}','SubscriptionController@destroy');
        });

        Route::group(['prefix' => 'notes'],function(){
            Route::get('get-list','NoteController@index');
            Route::get('get-list/{member_id}','MemberController@getMemberNotesList');
            Route::get('get-list/{member_id}','MemberController@getMemberNotesList');
            Route::post('create','NoteController@store');
            Route::post('update','NoteController@updateMemberNote');
            Route::post('delete/{id}','NoteController@destroy');
        });

        Route::group(['prefix' => 'templates'],function(){
            Route::post('create','TemplateController@store');
            Route::post('list','TemplateController@getList');
        });

        Route::group(['prefix' => 'reports'],function(){
            Route::post('generate-member-report','ReportController@memberReport');
            Route::get('get-operator-list','ReportController@reportOperators');
            Route::post('get-member-status-report','ReportController@memberStatusReport');
            Route::post('get-new-member-report','ReportController@newMemberReport');
            Route::post('get-member-birthdays-report','ReportController@memberBirthdaysReport');
            Route::post('get-member-renewal-report','ReportController@memberRenewalReport');
            Route::post('get-member-payments-report','ReportController@memberPaymentReport');
            Route::post('get-member-card-printing-report','ReportController@memberCardPrintingReport');
            Route::post('get-member-group-report','ReportController@memberGroupReport');
        });

        Route::group(['prefix' => 'draws'],function (){
            Route::post('create','DrawController@store');
            Route::post('list','DrawController@index');
            Route::post('/{id}/entries','DrawController@getEntriesListByDrawId');
            Route::get('/entry/{id}','DrawController@getDrawEntry');
            Route::delete('/delete/{id}','DrawController@deleteDraw');
        });

        Route::group(['prefix' => 'dashboard'],function (){
            Route::post('get-subscription-stats','DashboardController@getSubscriptionStats');
        });

        Route::group(['prefix' => 'kiosk-backgrounds'],function (){
            Route::post('get-all','Kiosk\BackgroundController@getList');

            Route::get('get-all-specific-backgrounds', 'Kiosk\BackgroundController@getSpecificList');
            Route::post('upload-or-update-background-image','Kiosk\BackgroundController@uploadOrUpdateSpecificBackground');
        });
        Route::group(['prefix' => 'voucher'],function(){
            Route::post('create-parameter','VoucherController@createVoucherParameter');
            Route::get('get-voucher-parameters-list','VoucherController@getVoucherParameterList');
            Route::post('generate-voucher','VoucherController@generateVoucher');
            Route::post('update-voucher-image','VoucherController@saveVoucherImage');
            Route::post('send-voucher-email','VoucherController@sendVoucherAsEmail');
            Route::post('voucher-check','VoucherController@voucherCheck');
            Route::post('voucher-validation','VoucherController@voucherValidation');
            Route::post('get-voucher-logs','VoucherController@getVoucherLogs');
            Route::get('get-vouchers-list/{promoId}','VoucherController@getVoucherList');
            Route::get('get-voucher-details/{voucherCode}','VoucherController@getVoucherDetails');
            Route::get('get-birthday-voucher-list','VoucherController@getBirthdayVouchers');
        });

        Route::group(['prefix' => 'advertising'],function(){
            Route::post('create','AdvertisingController@store');
            Route::post('update-order','AdvertisingController@updateSequence');
            Route::get('list','AdvertisingController@index');
            Route::get('/{id}','AdvertisingController@show');
            Route::delete('delete/{id}','AdvertisingController@destroy');
            Route::delete('delete-advertising-image/{id}','AdvertisingController@deleteAdvertisingImage');
            Route::delete('delete-advertising-images/{id}','AdvertisingController@deleteAllAdvertisingImages');
        });

        Route::group(['prefix' => 'coffee-card'],function(){
            
            Route::post('get-coffee-cards-stats', 'CoffeeCardController@coffeeCardsStats');
            Route::post('get-coffee-card-rewards-stats', 'CoffeeCardController@getRewardStats');
            Route::post('get-coffee-cards-for-dropdown', 'CoffeeCardController@getListForDropdown');
            Route::post('/get-list', 'CoffeeCardController@index');
            Route::post('/get-member-card-stamps-earnt','CoffeeCardController@checkMemberCoffeeCard');
            Route::post('/add-stamp','CoffeeCardController@addStamp');
            Route::get('/{cardCode}','CoffeeCardController@show');
            Route::post('/redeem-reward','CoffeeCardController@redeemMemberCoffeeCard');
        });

        Route::group(['prefix' => 'email-templates'],function(){
            Route::post('/send-email','EmailTemplateController@sendEmail');
            Route::delete('/delete/{id}','EmailTemplateController@deleteEmailTemplate')->middleware(['cors']);
        });
    });
});

/**
 *
 * end All routes of admin.
 */
Route::group(['prefix' => 'users'],function(){
    Route::get('get-user-types','UserController@getUserTypes');
});
Route::get('get-country-list', 'RecordController@getContryList');
Route::get('get-payment-frequency-list', 'DropdownController@getPaymentFrequency');
Route::get('get-country-code-list', 'RecordController@getContryCodeList');
Route::get('get-plan-list', 'RecordController@getPlanList');
Route::get('get-voucher-parameter-types', 'DropdownController@getVoucherParameterTypes');
Route::get('get-voucher-parameter-expires', 'DropdownController@getVoucherParameterExpires');
Route::get('get-voucher-parameter-expiry', 'DropdownController@getVoucherParameterExpiry');
Route::get('get-voucher-parameter-limited-quantity', 'DropdownController@getVoucherParameterLimitedQuantity');
Route::get('get-voucher-parameter-value', 'DropdownController@getVoucherParameterValue');
Route::get('get-voucher-parameter-value-mode', 'DropdownController@getVoucherParameterValueMode');
Route::get('get-kiosk-voucher-parameter-duration', 'DropdownController@getKioskVoucherParameterDuration');
Route::get('get-kiosk-voucher-parameter-frequency', 'DropdownController@getVoucherParameterFrequency');
Route::get('get-all-country-list-mb', 'DropdownController@getCountryListing');
Route::get('/verify/user/{token}','UserController@verifyAddUser');
Route::get('check-financial',function(){
    \App\Member::select('renewal','id')->chunk(100,function ($members){
        foreach ($members as $member) {
            $renewal = $member->renewal;
            if (!empty($renewal) && (new \Carbon\Carbon($renewal)) >= \Carbon\Carbon::now()){
                $member->financial = \App\base\IStatus::ACTIVE;
                $member->save();
            }else{
                $member->financial = \App\base\IStatus::INACTIVE;
                $member->save();
            }

        }
    });
});
