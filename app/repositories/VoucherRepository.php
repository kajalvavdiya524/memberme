<?php
/**
 * Created by PhpStorm.
 * User: Pc1-RandomFounder
 * Date: 12/18/2018
 * Time: 7:21 PM
 */

namespace App\repositories;


use App\base\IStatus;
use App\Exceptions\ApiException;
use App\Organization;
use App\Voucher;
use App\VoucherLog;
use App\VoucherParameter;
use Carbon\Carbon;
use File;
use FontLib\Table\Type\name;
use Intervention\Image\Image;
use Storage;

class VoucherRepository
{
    public function __construct()
    {
    }

    public function addVoucherParameter(Organization $organization,array $data)
    {
        $voucherParameter = [];
        if(!empty($data)){
            if(!empty(array_get($data,'id'))){
                $voucherParameter = VoucherParameter::find(array_get($data,'id'));
            }
            if(empty($voucherParameter)){
                $voucherParameter = new VoucherParameter();
            }
//            $voucherParameter->voucher_parameter_id = array_get($data,'voucher_parameter_id');
            $voucherParameter->voucher_type = array_get($data, 'voucher_type');
            $voucherParameter->voucher_name = array_get($data,'voucher_name');
            $voucherParameter->multisite = array_get($data,'multisite');
            $voucherParameter->multisite_organizations = array_get($data,'multisite_organizations');
            $voucherParameter->expires = array_get($data,'expires');
            $voucherParameter->expiry_mode = array_get($data,'expiry_mode');
            $voucherParameter->expiry_period_quantity = array_get($data,'expiry_period_quantity');
            $voucherParameter->expiry_period_duration = array_get($data,'expiry_period_duration');
            if(array_get($data,'expiry_date')){

                $expiryDate = str_replace('/','-', array_get($data,'expiry_date'));

                $expiryToSet = new Carbon($expiryDate);
                $voucherParameter->expiry_date = $expiryToSet->endOfDay();
            }
            $voucherParameter->uses = array_get($data,'uses');
            $voucherParameter->limited = array_get($data,'limited');
            $voucherParameter->limited_quantity = array_get($data,'limited_quantity');
            $voucherParameter->availability = array_get($data,'limited_quantity');
            $voucherParameter->min_value = array_get($data,'min_value');
            $voucherParameter->max_value = array_get($data,'max_value');
            $voucherParameter->value = array_get($data,'value');
            $voucherParameter->value_mode = array_get($data,'value_mode');

            //region  Restriction for value quantity is not more then 100 if value mode is %
            if(array_get($data,'value_mode') == VoucherParameter::VALUE_MODE['$']){
                $voucherParameter->value_quantity = array_get($data,'value_mode');
            }else if(array_get($data,'value_mode') == VoucherParameter::VALUE_MODE['%']){
                if(array_get($data,'value_quantity') <= 100 ){
                    $voucherParameter->value_quantity = array_get($data,'value_mode');
                }else{
                    throw new ApiException(null,['value_quantity' => 'Value quantity can not be more then 100 when value mode is %.']);
                }
            }
            //endregion

            $voucherParameter->value_quantity = array_get($data,'value_quantity');
            $voucherParameter->voucher_front_image = array_get($data,'voucher_front_image');
            $voucherParameter->voucher_back_image= array_get($data,'voucher_back_image');
            $voucherParameter->back_image_style = array_get($data,'back_image_style');
            $voucherParameter->front_image_style = array_get($data,'front_image_style');
            //ssetting status
            $voucherParameter->status = array_get($data,'status', IStatus::ACTIVE);
            // promo_id, voucher_code
            $promoId =$this->generatePromoId();
            $voucherParameter->promo_id = $promoId;
            $voucherParameter->save();
        }

        $organization->voucherParameters()->save($voucherParameter);

        return $voucherParameter;  // todo return voucherParameter.
    }

    /**
     * Generate Voucher based on voucher parameter options.
     *
     * @param Organization $organization
     * @param VoucherParameter $voucherParameter
     * @param array $data
     * @return Voucher
     * @throws \Exception
     */
    Public function generateVoucher(Organization $organization, VoucherParameter $voucherParameter, array $data){

        //region Calculation of voucher expiry if exists.
        $expiryToSet = null;
        if ($voucherParameter->expires == VoucherParameter::EXPIRES['Yes'] && $voucherParameter->expiry_mode == VoucherParameter::EXPIRY_MODE['Period'] ){
            $voucherExpireDuration = $voucherParameter->expiry_period_duration;
            $voucherExpireQuantity = $voucherParameter->expiry_period_quantity;
            $currentDate = new  Carbon();
            switch ($voucherExpireDuration){
                case VoucherParameter::EXPIRY_DURATION['Day']:
                    $expiryToSet = $currentDate->addDay($voucherExpireQuantity);
                        break;
                case VoucherParameter::EXPIRY_DURATION['Week']:
                    $expiryToSet = $currentDate->addWeek($voucherExpireQuantity);
                        break;
                case VoucherParameter::EXPIRY_DURATION['Month']:
                    $expiryToSet = $currentDate->addMonth($voucherExpireQuantity);
                        break;
                case VoucherParameter::EXPIRY_DURATION['Year']:
                    $expiryToSet = $currentDate->addYear($voucherExpireQuantity);
                    break;
            }
        }
        else if ($voucherParameter->expires == VoucherParameter::EXPIRES['Yes'] && $voucherParameter->expiry_mode == VoucherParameter::EXPIRY_MODE['Date']){
            $expiryToSet = new Carbon($voucherParameter->expiry_date);
        }

        if(!empty($expiryToSet)) $expiryToSet = $expiryToSet->endOfDay();
        //endregion

        //region Voucher variable resolving
        if(!empty(array_get($data,'id'))){
            $voucher = Voucher::find(array_get($data,'id'));
        }
        if(empty($voucher)){
            $voucher = new Voucher();
            $voucherCode = $this->generateVoucherCode();
            $voucher->voucher_code = $voucherCode;
        }

        $voucher->member_id = array_get($data,'member_id');
        //endregion

        //region Status To Set
        $statusToSet = null;
        $date = new Carbon();
        $statusToSet = VoucherParameter::VOUCHER_STATUS['Valid'];
        if(!empty($expiryToSet)){
            if($date > $expiryToSet) {
                $statusToSet = VoucherParameter::VOUCHER_STATUS['Expired'];
            }
        }
        //endregion

        if(!empty(array_get($data,'purchase_date'))){
            $voucherPurchaseDate = array_get($data,'purchase_date');
            if(!empty($voucherParameter)){
                $voucherPurchaseDate = str_replace('/','-',$voucherPurchaseDate);
            }
        }
//        $voucherMember = $organization->members()->where('members.id' , array_get($data,'member_id'))->select('members.id','members.email')->first();
//
//        if(empty($voucherMember)){
//            $voucherMember = $organization->members()->where('members.email' , array_get($data,'customer_email'))->select('members.id','members.email')->first();
//        }

//        if(empty($voucherMember)){
//            throw new ApiException(null,['error' => 'Invalid Member Selected']);
//        }
        $voucher->voucher_parameter_id = array_get($voucherParameter,'id');
        $voucher->voucher_name = array_get($voucherParameter,'voucher_name');
        $voucher->organization_id = array_get($organization,'id');
        $voucher->customer_name = array_get($data,'customer_name');
        $voucher->customer_email = array_get($data,'customer_email');
        if(!empty($voucherPurchaseDate)){
            $voucher->purchase_date = new Carbon($voucherPurchaseDate);
        }else{
            $voucher->purchase_date = Carbon::now();
        }
        $voucher->expiry_date = ($expiryToSet) ? $expiryToSet->endOfDay() : null;
        $voucher->status = $statusToSet ?? null;
        $voucher->validations_made = 0;     // 0 at creation
        if(array_get($voucherParameter,'uses') != VoucherParameter::USES['Unlimited'] && !empty($voucherParameter->uses)){
            $voucher->allowed_validations = array_get($voucherParameter,'uses');  // default voucher uses.
            $voucher->validations_left = array_get($voucherParameter,'uses');       // default voucher uses.
        }

        if(!empty(array_get($data,'variable_value'))){
            $voucher->voucher_value = array_get($data,'variable_value');
            $voucher->voucher_balance = array_get($data,'variable_value');
        }else{
            $voucher->voucher_value = $voucherParameter->value_quantity; // changed from voucher parameter's value to value quantity.
            $voucher->voucher_balance = $voucherParameter->value_quantity; // changed from voucher parameter's value to value quantity.
        }

        $voucher->value_mode = array_get($voucherParameter,'value_mode');

        if($voucherParameter->value_mode == VoucherParameter::VALUE_MODE['$']){
//            $voucher->voucher_balance = $voucherParameter->value_quantity;
        }

        $voucher->qr_code = $this->generateQrCode($organization, $voucher);
        $voucher->save();

        $voucherParameter->availability -= 1;
        $voucherParameter->save();

        $voucher = Voucher::whereId($voucher->id)->with(
            [
                'organization' => function($q){
                    $q->select('id','name');
                },
                'voucherParameter' => function($q){
                    $q->select('id','promo_id');
                }
            ])->first();

        return $voucher;
    }


    /**
     * This function will generate 8 digit code , 0-9 with A and C
     *
     * @param int $length
     * @return null|string
     */
    public function generateVoucherCode( $length = 8)
    {
        $voucherCode = null;
        $arrayA = array('A','C','A','C','A');
        do{
            $code = array_merge($arrayA, range(0, 9));
            $max = count($code) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $voucherCode .= $code[$rand];
            $isAvailable = Voucher::whereVoucherCode($voucherCode)->first();
            }
        } while(!empty($isAvailable));
        return $voucherCode;
    }

    /**
     * 8 digit random unique promo number
     *
     * @return int
     */
    public function generatePromoId()
    {
        do{
            $promoId = rand(10000000, 99999999);
            $isAvailable = VoucherParameter::wherePromoId($promoId)->first();
        }while(!empty($isAvailable));
        return $promoId;
    }

    /**
     * Voucher Validation.
     *
     * @param Voucher $voucher
     * @param $amountToValidate
     * @return Voucher
     * @throws ApiException
     */
    public function validateVoucher(Voucher $voucher, $amountToValidate)
    {

        $voucherParameter = $voucher->voucherParameter;

        if($voucher->status == Voucher::VOUCHER_STATUS['Validated']){
            throw new ApiException($voucher,null,'Voucher is already validated');
        }

        if($voucher->expiry_date && $voucherParameter->expires == VoucherParameter::EXPIRES['Yes']){
            if(new Carbon($voucher->expiry_date) <= Carbon::now()){
                $voucher->status = Voucher::VOUCHER_STATUS['Expired'];
                throw new ApiException($voucher,null,'Voucher has been expired');
            }
        }

        if ($amountToValidate) {
            if ($voucher->voucher_balance < $amountToValidate) {
                throw new ApiException(['amount_to_redeem' => 'Unable to Validate, the voucher balance is insufficient, The current balance is ' . $voucher->voucher_balance]);
            }
        }

        if($voucher->allowed_validations > $voucher->validations_made || $voucher->voucherParameter->uses == VoucherParameter::USES['Unlimited']){
            if($voucher->voucher_balance == 0 ){
                $voucher->status = Voucher::VOUCHER_STATUS['Validated'];
                if($voucherParameter->uses != VoucherParameter::USES['Unlimited']){
                    $voucher->validations_left = ($voucher->validations_left == 0 ) ? 0 : $voucher->validations_left - 1 ;
                }
                $voucher->validations_made += 1;
                $voucher->last_validations = Carbon::now();
                $voucher->save();
            }else{

                if($voucherParameter->uses != VoucherParameter::USES['Unlimited']){
                    $voucher->validations_left = ($voucher->validations_left == 0 ) ? 0 : $voucher->validations_left - 1 ;
                }

                $voucher->voucher_balance -= $amountToValidate;
                $voucher->validations_made += 1;
                $voucher->last_validations = Carbon::now();

                if($voucher->voucher_balance <= 0){
                    $voucher->status = Voucher::VOUCHER_STATUS['Validated'];
                }

                $voucher->save();
            }

            //region If Voucher has been validated more then its uses then changing status to validated.
            if($voucher->allowed_validations <= $voucher->validations_made && $voucherParameter->uses != VoucherParameter::USES['Unlimited']){
                $voucher->status = Voucher::VOUCHER_STATUS['Validated'];
                $voucher->save();
            }
            //endregion

        }else{
            $voucher->status = Voucher::VOUCHER_STATUS['Validated'];
            $voucher->validations_left = 0;
            $voucher->save();

            throw new ApiException($voucher,null,'Voucher have been used used more then its limit');
        }

        $voucher->addVoucherLog($amountToValidate);

        return $voucher;
    }


    /**
     * This function will generate the qr code of voucher code, save the png in specific folder  and then return the qr code url.
     * @param Organization $organization
     * @param Voucher $voucher
     * @return string
     */
    public function generateQrCode(Organization $organization, Voucher $voucher)
    {
        $voucherCode = $voucher->voucher_code;
        $path = '/'.$organization->name.'-voucher-QrCodes';
        $filename = '/'.$voucherCode.'-'.time().'.png';
        if(File::isDirectory(Storage::disk('local')->path($path ))){
            $qrCode = \QrCode::format('png')->margin(0)->size(200)->generate($voucherCode,Storage::disk('local')->path($path.$filename ));
        }
        else{
            File::makeDirectory(Storage::disk('local')->path($path ));
            $qrCode = \QrCode::format('png')->margin(0)->size(200)->generate($voucherCode,Storage::disk('local')->path($path.$filename  ));
        }

        $qrCodeUrl = Storage::disk('local')->url($path.$filename);

        return $qrCodeUrl;

    }

    public function getMemberVoucherCards($email)
    {
        $vouchers = Voucher::whereStatus(Voucher::VOUCHER_STATUS['Valid'])
            ->whereCustomerEmail($email)
            ->whereNotNull('front_image')
            ->select('front_image','qr_code','voucher_code')
            ->get();
        return $vouchers;

    }

    public function writeTextOnVoucherImage(Image $voucherImage,$text,$coordinatesData = [ 'x' => 0 , 'y' => 0 ],$fontData = []){
        $voucherTitleX = array_get($coordinatesData,'x');
        $voucherTitleY = array_get($coordinatesData,'y');

        $voucherImage->text($text ,$voucherTitleX,$voucherTitleY,function ($font) use ( $fontData ) {
            $font->color(array_get($fontData,'color','#000'));
            $fontFile =  (!empty(array_get($fontData,'file')))? array_get($fontData,'file') :  public_path('Rubik-light.ttf');
            $font->file($fontFile);
            $fontSize = 14;
            if(array_has($fontData,'font-size')){
                if(is_numeric($fontData['font-size'])){
                    $fontSize = $fontData['font-size'];
                }else{
                    $fontSize = substr($fontData['font-size'], 0, strpos($fontData['font-size'], 'p'));
                }
            }
            if($fontSize){
                $font->size($fontSize);
            }else{
                $font->size(14);
            }
//        $font->align('center');
//        $font->valign('top')
        });

        return $voucherImage;
    }

    /**
     * Return the array of design for the elements
     * @param $designString string
     * @return array | string
     *
     */
    public function prepareDesignData($designString)
    {
        try{
            $design = explode(';',$designString);
            $designArray = [];
            foreach ($design as $item) {
                $singleItem = explode(':',$item);
                $designArray [trim(array_get($singleItem,0))] = trim(array_get($singleItem,1));
            }
            return $designArray;
        }catch (\Exception $exception){
            return [];
        }

    }

    /**
     * @param Voucher $voucher
     * @return null|string
     */
    public function prepareVoucherValue(Voucher $voucher)
    {
        $voucherValue = $voucher->voucher_value;
        $voucherValue = 'Amount: $'.$voucherValue;
        return $voucherValue;
    }
}