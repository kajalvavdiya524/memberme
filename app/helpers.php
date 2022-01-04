<?php
/**
 * Created by PhpStorm.
 * User: Faisal
 * Date: 1/20/2018
 * Time: 11:50 AM
 */

function api_response($data,$errors = null ,$message = null,$responseCode = 200,$pending_org = \App\base\IStatus::ACTIVE, $headers = [], $options = 0){
    $result = \App\Helpers\ApiHelper::apiResponse($data,$errors,$message);
    if($pending_org == \App\base\IStatus::INACTIVE){
        unset($result['pending_org']);
    }
    try{
        return response()->json($result, $responseCode,$headers, $options);
    }catch (\Exception $exception){
        print_r($exception->getMessage());exit;
    }
}

function api_error($errors = [],$responseCode = \App\base\IResponseCode::INVALID_PARAMS){
    $result = \App\Helpers\ApiHelper::apiResponse(null,$errors);
    return response()->json($result,$responseCode);
}

function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
        && (1 === count($node) ? $node = array($node) : 1)
        && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}


/**
 * @param $data
 * @return string
 */
function serialize_data($data)
{
    return $data ? base64_encode(serialize($data)) : Null;
}

/**
 * @param $data
 * @return mixed
 */
function un_serialize_data($data)
{
    try {
        return $data ? unserialize(base64_decode($data)) : Null;
    } catch (Exception $exception) {
        $base64DecodedObject = base64_decode($data);
        $preparedObject = rtrim(substr($base64DecodedObject, strpos($base64DecodedObject, '"') + 1), '";');
        return $preparedObject;
    }

}

/**
 * @param $contactNo
 * @return mixed|string
 */
function prepare_number($contactNo){
    $explodedNumber = explode('-',$contactNo);
    $preparedNumber = ltrim($contactNo,'0');
//    $preparedNumber = str_replace('-','', $preparedNumber);
    $first = isset($explodedNumber[0]) ? $explodedNumber[0]:null;
    $last = ltrim(array_get($explodedNumber,'1'),'0');
    $preparedNumber = $first . $last;
    $preparedNumber = str_replace('+','', $preparedNumber);
    return $preparedNumber;
}


function write_import_logs($organizationName , $row = [], $isOverride = false, $message = null){
    if(!empty($message)){
        $row[] = $message;
    }
    $directory = '/import-logs/';
    $fileName = urlencode($organizationName).'.csv';
    $fileNameWithDirectory = $directory.''.$fileName;
    $directoryPath = Storage::disk('local')->path($directory); // /var/www/html/memapp/storage/public/app/images/import-logs
    $fileNameWithPath = $directoryPath.'/'.$fileName;

    if(File::exists($fileNameWithPath)){
        if($isOverride){
            $handle = fopen($fileNameWithPath,'w');
        }else{
            $handle = fopen($fileNameWithPath,'a');
        }
        fputcsv($handle,$row);
        fclose($handle);
    }else{
        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath);
        }

        if($isOverride){
            $handle = fopen($fileNameWithPath,'w');
        }else{
            $handle = fopen($fileNameWithPath,'a');
        }

        fputcsv($handle,$row);
        fclose($handle);
    }

    try{
        $url = Storage::disk('local')->url($fileNameWithDirectory);
        return $url;
    }catch (Exception $exception){
        return null;
    }

}

/**
 * This function will upload the file and will return the image's url afer uploading that.
 ** @param \Illuminate\Http\UploadedFile $uploadedFile this parameter will be the input file.
 * @param $folderName
 * @return null|string
 */
function uploadFile(\Illuminate\Http\UploadedFile $uploadedFile,$folderName){
    if($uploadedFile){
        $name = $uploadedFile->getClientOriginalName();
        $name = md5($name) . '.' . $uploadedFile ->getClientOriginalExtension();
        $path = '/'.$folderName.'/' . $name;
        Storage::put($path, File::get($uploadedFile->getRealPath()));
        $url = Storage::disk('local')->url($path);
        return $url;
    }else{
        return null;
    }
}

/**
 * Generate qr code at given path and then return the url for that code.
 *
 * @param $path
 * @param $code
 * @return string
 */
function qrCodeGenerate($path, $code){
    $filename = '/'.$code.'-'.time().'.png';
    if(File::isDirectory(Storage::disk('local')->path($path ))){
        $qrCode = \QrCode::format('png')->margin(0)->size(200)->generate($code,Storage::disk('local')->path($path.$filename ));
    }
    else{
        File::makeDirectory(Storage::disk('local')->path($path ));
        $qrCode = \QrCode::format('png')->margin(0)->size(200)->generate($code,Storage::disk('local')->path($path.$filename  ));
    }

    $qrCodeUrl = Storage::disk('local')->url($path.$filename);

    return $qrCodeUrl;
}

/**
 * Get Timezone of organization.
 * @param \App\Organization $organization
 * @return string|null
 */
function getTimeZone(\App\Organization $organization){
    $timezone = 'Pacific/Auckland';
    if(!empty($organization->timezone->timezone)){
        $timezone = $organization->timezone->timezone;
    }
    return $timezone;
}

/**
 * Setting default timezone of application as per timezone provided or Pacific/Auckland
 * @param string $timezone
 */
function setTimezone($timezone = 'Pacific/Auckland'){
    try {
        date_default_timezone_set($timezone);
    }catch (Exception $exception)
    {
        Log::error('Unable to Set timezone'. $timezone. ' Setting Pacific/Auckland');
        date_default_timezone_set('Pacific/Auckland');
    }
}

function writeOnImage(Intervention\Image\Image $image, $text = '', $x = 0 , $y = 0 , $fontData = []){

    $image->text($text ,$x, $y, function ($font) use ( $fontData ) {

        /** @var $font \Intervention\Image\Gd\Font */
        $font->color(array_get($fontData,'color','#000'));
        $fontFile =  (!empty(array_get($fontData,'file')))? array_get($fontData,'file') :  public_path('times-new-roman.ttf');
        $font->file($fontFile);
        $fontSize = 16;
        if(array_has($fontData,'font-size')){
            if(is_numeric($fontData['font-size'])){
                $fontSize = $fontData['font-size'];
            }else{
                $fontSize = array_get($fontData,'font-size');
            }
        }
        if(!empty($fontData['angle'])){
            $font->angle($fontData['angle']);
        }
        if($fontSize){
            $font->size($fontSize);
        }else{
            $font->size(14);
        }

    });

    return $image;

}