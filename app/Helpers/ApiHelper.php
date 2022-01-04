<?php
/**
 * Created by PhpStorm.
 * User: Feci
 * Date: 9/18/2017
 * Time: 7:07 PM
 */

namespace App\Helpers;


use App\base\IResponseCode;
use App\base\IStatus;
use App\repositories\OrganizationRepository;
use App\repositories\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiHelper
{

    /**
     * @param $data
     * @param null $errors
     * @param null $message
     * @return array
     */
    public static function apiResponse($data = [],$errors = null, $message=null)
    {
        $result = [];
        $metaData = [
            'status' => '',
        ];
        //if there is an error send that error with accurate response.
        if($errors != null){
            $metaData['status'] = IStatus::FAILED;
            $result['_metadata'] = $metaData;
            $result['errors'] = $errors;
            return $result;
        }

        //if user logged in,
        if($data instanceof User){
            $user = self::getApiUser($data);
        }else{
            try{
                $user = self::getApiUser();
            }catch (\Exception $exception){
//                \Log::info('User not Found by helper.');
            }
        }
        if(!empty($user)){
            /* @var $orgRepo OrganizationRepository*/
            $orgRepo = new OrganizationRepository();
            $org = $orgRepo->getPendingOrg($user);

            $metaData['status'] = IStatus::PASSED;
            $result['_metadata'] = $metaData;

            if(empty($org))
            {
                $result['pending_org'] = $org;
            }else{
                //if user is not activated till now, activate that user,
                if($user->activate != IStatus::ACTIVE){
                    $userRepository = new UserRepository();
                    /* @var $userRepository */
                    $userRepository->toggleActivation($user, IStatus::INACTIVE);
                }else{
                    $result['pending_org'] = $org;
                    $result['message'] = 'You have pending organization, You have to fill that details first';
                }
            }

            //region Refreshing Token with each request.
            try {
                if(empty($errors)){
//                    $token =JWTAuth::parseToken()->refresh();
                    // Send the refreshed token back to the client.
//                    $result['_rft'] = $token;
                }
            } catch (JWTException $e) {
//                throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
            }
            //endregion
            

            $result['data'] = $data;
            $result['message'] = $message;
            return $result;
        }else{
            $metaData['status'] = IStatus::PASSED;
            $result['_metadata'] = $metaData;
            $result['data'] = $data;
            $result['message'] = $message;
            return $result;
        }
    }

    /**
     * @throws TokenExpiredException
     */
    public static function getApiUser(User $user = null)
    {
        if(!empty($user)){
            return $user;
        }
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(TokenExpiredException $exception){
            throw new TokenExpiredException('Session has expired');
        }catch (TokenBlacklistedException $tokenBlacklistedException){

        }catch (\Exception $exception){
            \Log::info($exception->getMessage());
        }
        return $user;
    }

    public static function apiLogout(){
        try{
            JWTAuth::invalidate(JWTAuth::getToken());
        }catch (\Exception $exception){
            \Log::info($exception->getMessage() );
        }

    }
}
