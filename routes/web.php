<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Member;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use JonnyW\PhantomJs\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

Route::get('regenerate-qr-code', function(){
    /** @var \App\repositories\MemberRepository $memberRepository */
    $memberRepository = new \App\repositories\MemberRepository();

   Member::whereNotNull('validate_id')->chunk(50, function ($members) use ($memberRepository) {
       foreach ($members as $member) {
               $qrCodeLink = qrCodeGenerate('member-qr-codes-', $member->validate_id);
               $member->qr_code = $qrCodeLink;
               $member->save();
       }
   });
});
Route::get('coffeeCard/{cardId}',function($cardId){
    /** @var \App\CoffeeCard $coffeeCard */
    $memberCoffeeCard = \App\MemberCoffeeCard::find($cardId);
    /** @var \App\repositories\CoffeeCardRepository $coffeeCardService */
    $coffeeCardService = new \App\repositories\CoffeeCardRepository();
    $coffeeCardImage = $coffeeCardService->generateCoffeeCardImage($memberCoffeeCard,8,1);
    return $coffeeCardImage->response('jpg');
});

Route::get('/show-member-card/{memberId}',function($memberId, Request $request){
    /* @var $member Member */
    $member = Member::find($memberId);

    /* @var  $memberTemplate \App\OrganizationCardTemplate*/
    $memberTemplate = $member->template()->first();
    $data ['full_name'] = $member->first_name. ' '. $member->last_name;
    $data ['expiry_date' ] = $member->renewal;
    $data ['identity' ] = $member->identity;
    $data ['subscription' ] = $member->subscription()->first()->title;
    $data ['member_id' ] = $member->member_id;
    $data ['template' ] = $memberTemplate;
    if(!empty($memberTemplate)){
        $data ['nameStyle'] = $memberTemplate->style['_name2'];
        $data ['nameLabelStyle'] = $memberTemplate->style['_name'];

        $data ['subscriptionStyle'] = $memberTemplate->style['_subscription2'];
        $data ['subscriptionLabelStyle'] = $memberTemplate->style['_subscription'];

        $data ['memberNumberStyle'] = $memberTemplate->style['_number2'];
        $data ['memberNumberLabelStyle'] = $memberTemplate->style['_number'];

        $data ['expiryStyle'] = $memberTemplate->style['_expiry2'];
        $data ['expiryLabelStyle'] = $memberTemplate->style['_expiry'];

        $data ['codeStyle'] = $memberTemplate->style['_image'];
        $data ['profileImageStyle'] = $memberTemplate->style['_image2'];

        $data ['backgroundImageUrl'] = $memberTemplate->url;
    }
    return view('show_member_card',['data' => $data]);
});


Route::get('time', function(){return \Carbon\Carbon::now();});

Route::get('/email-verified',function(){
    return view('email-verified');
})->name('email-verified');
Route::get('member-change-email-verification/{token}','MemberController@verifyChangeEmail')->name('memberEmailChangeVerification');
Route::get('member-change-password-verification/{token}','MemberController@memberResetPassword')->name('verifyMemberPasswordReset');
Route::get('user-change-password-verification/{token}','UserController@userResetPassword')->name('resetUserPassword');
Route::post('reset-member-password','MemberController@resetMemberPassword')->name('memberResetPassword');
Route::post('reset-user-password','UserController@resetUserPassword')->name('userResetPassword');
Route::get('/coordinates',function(){
    /** @var \App\VoucherParameter $voucherParameter */
    $voucherParameter = \App\VoucherParameter::orderBy('id','desc')->first();

    echo "<pre>";
    print_r($voucherParameter->front_image_style);exit;

});

Route::get('soap',function() {
    /** @var Member $member */
    $member = Member::first();
//    $memberRepo = new \App\repositories\MemberRepository();
//    $memberRepo->pushmemberToPos($member->organization,$member);
    $untilService = new \App\Services\Untill\UntillService(\App\Organization::find(15550));
    $result = $untilService->getClients();
    dd($result);
    //endregion
});

Route::get('/', function () {

//    $memberRepository->getUntillExClient();

    return 'Memberme Web Application';
    $template = \App\OrganizationCardTemplate::find(5);
    $member = Member::first();
    /** @var \App\repositories\MemberRepository $memberRepository */
    $memberRepository = new \App\repositories\MemberRepository();
    $image = $memberRepository->generateMemberCard($template,$member);
    return $image->response('jpg');
    dd($template->toArray());

    /*$startOfTheDay = \Carbon\Carbon::now()->startOfDay()->format('H:i:s');
    $firstHalfOfFirstHour = \Carbon\Carbon::now()->startOfDay()->addMinute(30);
    $secondHalfOfFirstHour = \Carbon\Carbon::now()->startOfDay()->addHour(1);
    $firstHalfOf2ndHour = \Carbon\Carbon::now()->startOfDay()->addHour(1)->addMinute(30);
    $secondHalfOf2ndHour = \Carbon\Carbon::now()->startOfDay()->addHour(2);

    $memberCoffeeCardJoin = 'left join member_coffee_cards on member_coffee_card_id = member_coffee_cards.id ';

//    dd($firstHalfOfFirstHour);
    DB::enableQueryLog();
    $stats = DB::select('Select
        (
            Select count(*) from member_coffee_card_logs
            WHERE date(stamp_added_time) = "'.\Carbon\Carbon::now()->format("Y-m-d").'"
            AND time(stamp_added_time) BETWEEN "'.$startOfTheDay.'" AND "'.$firstHalfOfFirstHour->format('H:i:s').'"
        ) AS first_of_00,
        (
            Select count(*) from member_coffee_card_logs
            WHERE date(stamp_added_time) = "'.\Carbon\Carbon::now()->format("Y-m-d").'"
            AND time(stamp_added_time) BETWEEN "'.$firstHalfOfFirstHour->addMinute(1)->format('H:i:s').'" AND "'.$secondHalfOfFirstHour->format('H:i:s').'"
        ) AS second_of_00,
        (
            Select count(*) from member_coffee_card_logs
            WHERE date(stamp_added_time) = "'.\Carbon\Carbon::now()->format("Y-m-d").'"
            AND time(stamp_added_time) BETWEEN "'.$secondHalfOfFirstHour->addMinute(1)->format('H:i:s').'" AND "'.$firstHalfOf2ndHour->format('H:i:s').'"
        ) AS first_of_01,
        (
            Select count(*) from member_coffee_card_logs
            WHERE date(stamp_added_time) = "'.\Carbon\Carbon::now()->format("Y-m-d").'"
            AND time(stamp_added_time) BETWEEN "'.$firstHalfOf2ndHour->addMinute(1)->format('H:i:s').'" AND "'.$secondHalfOf2ndHour->format('H:i:s').'"
        ) AS second_of_01
    FROM member_coffee_card_logs limit 1');
    dd($stats);
    return 'Memberme Api';*/
    $sendgridService = new \App\Services\Sendgrid\SendgridService();
    $sendgridService->setup(\App\Organization::find(15550));

    $emailAddress = new \SendGrid\Mail\EmailAddress(
        'faisaldeveloper7@gmail.com','Faisal Arif',
        [
            'first_name' => 'Faisal',
            'last_name' => 'Arif',
            'organization_name' => 'RandomFounders'
        ],
        'New Member'
    );

    $sendgridService->addEmailWithSubstitutions($emailAddress);
    exit;

    $dynamicParameters = [
        'first_name' => 'Faisal',
        'last_name' => 'Arif',
        'organization_name' => 'RandomFounders'
    ];


    $toEmails = $sendgridService->composeTos('faisaldeveloper7@gmail.com');
    $toEmails = $sendgridService->composeTos('fslarif77@gmail.com',' ', $toEmails);
    $email = $sendgridService->setupMail($toEmails, $dynamicParameters );
    $email = $sendgridService->setTemplateId(
        $email
        ,'d-96da0ca102f1487da0990e4366f3426e'
    );

    dd($email);
    $sendgridServi>send($email);
    exit;
    /** @var \App\VoucherParameter $voucherParameter */
    $voucherParameter = \App\VoucherParameter::orderBy('id','desc')->first();
    /** @var \App\Voucher $voucher */
    $voucher = \App\Voucher::orderBy('id','desc')->first();
    /** @var \App\repositories\VoucherRepository $voucherRepository */
    $voucherRepository = new \App\repositories\VoucherRepository();
    /** @var \App\Organization $organization */
    $organization = $voucher->organization()->first();
    /** @var \App\OrganizationDetail $organizationDetails */
    $organizationDetails = $organization->details;
    /** @var \App\Address $organizationAddress */
    $organizationAddress = $organizationDetails->physicalAddress;

    $frontImageStyle =  json_decode($voucherParameter->front_image_style,true);
    $image = Image::make(asset($voucherParameter->voucher_front_image))->resize(432, 275,function($constraint){
//        $constraint->aspectRatio();
        $constraint->upsize();
    });

    //region Voucher Qr Code Placement
    $qrCode = Image::make($voucher->qr_code)->resize(80,80,function($constraint){
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $qrCodeX = (integer)$frontImageStyle['scaling_points']['qr_image']['x'];
    $qrCodeY = (integer)$frontImageStyle['scaling_points']['qr_image']['y'];
    $image->insert($qrCode, 'top-left',$qrCodeX,$qrCodeY);  //writing qrCode to the canvas
    //endregion

    //region Voucher Title Placement
    $voucherTitleCoordinates = $frontImageStyle['scaling_points']['title'];
    $designArray = $voucherRepository->prepareDesignData(array_get($frontImageStyle,'_title_json'));
    $designArray['file'] = public_path('Rubik-regular.ttf');
    $voucherRepository->writeTextOnVoucherImage($image,$voucherParameter->voucher_name,$voucherTitleCoordinates,$designArray);
    //endregion


    //region OrganizationName Placement
    $voucherTitleCoordinates = $frontImageStyle['scaling_points']['organization_name'];
    $voucherRepository->writeTextOnVoucherImage($image,$organization->name,$voucherTitleCoordinates,['font-size' => 15]);
    //endregion

    //region OrganizationAddress1 Placement
    $organizationAddress1Coordinates = $frontImageStyle['scaling_points']['organization_address1'];
    $voucherRepository->writeTextOnVoucherImage($image,$organizationAddress->address1,$organizationAddress1Coordinates ,['font-size' => 15]);
    //endregion

    //region OrganizationAddress2 Placement
    $organizationAddress2Coordinates = $frontImageStyle['scaling_points']['organization_address2'];
    $voucherRepository->writeTextOnVoucherImage($image,$organizationAddress->city . ' ' . $organizationAddress->postal_code ,$organizationAddress2Coordinates, ['font-size' => 15]);
    //endregion

    //region Organization Phone Placement
    $organizationPhoneCoordinates = $frontImageStyle['scaling_points']['organization_phone'];
    $voucherRepository->writeTextOnVoucherImage($image,'Ph: '. $organizationDetails->contact_phone,$organizationPhoneCoordinates, ['font-size' => 15]);
    //endregion

    //region VoucherPrice Placement
    $voucherPriceCoordinates = $frontImageStyle['scaling_points']['price'];
    $voucherPriceDesignArray = $voucherRepository->prepareDesignData(array_get($frontImageStyle,'_price_json'));
    $voucherPriceDesignArray ['file'] = public_path('Rubik-regular.ttf');
    $voucherRepository->writeTextOnVoucherImage($image,$voucherRepository->prepareVoucherValue($voucher),$voucherPriceCoordinates , $voucherPriceDesignArray);
    //endregion

    //region Issued Date Placement
    $issuedDateCoordinates = $frontImageStyle['scaling_points']['issued_date'];

    $issuedDesignArray = $voucherRepository->prepareDesignData(array_get($frontImageStyle,'_issued_json'));
    $voucherRepository->writeTextOnVoucherImage($image,'Issued: '.date('d/m/Y',strtotime($voucher->purchase_date )),$issuedDateCoordinates  , $issuedDesignArray );
    //endregion

    //region OrganizationAddress1 Placement
    $expiryDateCoordinates = $frontImageStyle['scaling_points']['expiry_date'];
    $expiredDesignArray = $voucherRepository->prepareDesignData(array_get($frontImageStyle,'_expiry_json'));
    $voucherRepository->writeTextOnVoucherImage($image,'Expires: '. date('d/m/Y',strtotime($voucher->expiry_date)),$expiryDateCoordinates  , $expiredDesignArray);
    //endregion

    //region VoucherCode Placement
    $voucherCodeToShow = rtrim(implode("-", str_split($voucher->voucher_code, 4)),'-');
    $voucherCodeCoordinates = $frontImageStyle['scaling_points']['code'];
    $voucherCodeDesignData = $voucherRepository->prepareDesignData(array_get($frontImageStyle,'_code_json'));
    $voucherCodeDesignData['font-size'] = 13;
    $voucherRepository->writeTextOnVoucherImage($image,$voucherCodeToShow,$voucherCodeCoordinates ,$voucherCodeDesignData);
    //endregion


//    $mask = Image::canvas($width, $height);
//
//     draw a white circle
//    $mask->rectangle(10, 10, 190, 190, function ($draw) {
//        $draw->background('#fff');
//    });

//    $qrCode->mask($mask,false);


//    $voucherCodeImage = Image::make($voucher->voucher_code)->resize($voucherCodeWidth,300,function($constraint){
//        $constraint->aspectRatio();
//        $constraint->upsize();
//    });
    return $image->response('jpg');
    $img->insert($logo, 'top-left', 10, 10);

    return 'Memberme Api';
});

//Route::get('voucher-check','VoucherController@voucherCheck');
Route::get('s',function(){
    $member = Member::where(['member_id' => 602, 'organization_id' => 15562 ])->first();
    $organization = \App\Organization::find(15562);

    /** @var \App\Services\Untill\UntillService $untilService */
    $untilService = new \App\Services\Untill\UntillService($organization);

    dd($member->untill_data);

});

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('storage/organization/{folder_name}/{filename}',function($folder_name, $filename){
    $path = config('filesystems.disks.local.root').'/'.$folder_name.'/'. $filename;
    if(!File::exists($path))
        abort(404);
    $file = File::get($path);
    $type = File::mimeType($path);
    ob_end_clean();
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});



Route::get('/home', 'HomeController@index')->name('home')->middleware('org_check');

Route::get('/registered',['uses' => 'UserController@check','as' => 'user.check']);

Route::get('organization/{id}/details' ,['uses' => 'OrganizationController@getDetails' ,'as' => 'org_details']);
Route::get('user/{id}/details' ,['uses' => 'UserController@getDetails' ,'as' => 'user_details']);

Route::post('/saveOrganization',['uses' => 'OrganizationController@saveDetails','as' => 'save.org.details']);
Route::post('/saveUserDetails',['uses' => 'UserController@saveDetails','as' => 'save.user.details']);

Route::get('/verifyEmailFirst',['uses' => 'UserController@verifyEmail', 'as' => 'verifyEmailFirst']);

Route::get('/verify/{email}/{verifyToken}',[ 'uses'=>'Auth\RegisterController@sendEmailDone', 'as' => 'sendEmailDone']);

Route::get('s-c/{url}',function($url){

    $client = Client::getInstance();
//    $client->setBinDir('../bin');

    $client->isLazy();
    $client->getEngine()->setPath(base_path('bin/phantomjs.exe'));

    $request  = $client->getMessageFactory()->createCaptureRequest('https://validateconz.ipage.com/Memberme/index.html');
//    $request->setDelay(20);
    $request->setTimeout(10000);
    $response = $client->getMessageFactory()->createResponse();

    $file = '../bin/file.jpg';

    $request->setOutputFile($file);

    $client->send($request, $response);

    rename('../bin/file.jpg','screenshots/file.jpg');

    echo '<img src="'.asset('/screenshots.jpg').'"> ';
});
Route::get('/redis',function(){
});