<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Validator;
use Validator;
use Config as config;
class ApiAuthController extends BaseController
{
    public function login(Request $request)
    {
        $response_status_code = new Response();
        try {
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $authUser = Auth::user();
                //$success['token'] =  $authUser->createToken('WatchibaApiTest')->plainTextToken;
                $statusCode = $response_status_code::HTTP_ACCEPTED; // or $statusCode = 202.  Api_web_portal.status is the config file Api_web_portal/status.php. __('api.'.$statusCode) is the corresponding lang value of the lang key 202; in lang/en/api.php
                $success = [
                    //'token' => $authUser->createToken('WatchibaApiTest')->plainTextToken,
                    'name' => $authUser->name,
                    'email' => $authUser->email,
                    'password' => $authUser->getAuthPassword(),
                    'text' => __('api.'.$statusCode)
                ];
                //return $this->sendApiResponse($success, [config::get('Api_web_portal.status')[$statusCode] => __('api.'.$statusCode)], $statusCode);
                //return $this->sendApiResponse($success, [__('api.'.$statusCode)], $statusCode);

                return $this->sendApiResponse($success, $statusCode);
            }
            else{
                //$statusCode = http_response_code(401);
                $statusCode = $response_status_code::HTTP_UNAUTHORIZED; // or $statusCode = 401
                $error = __('api.'.$statusCode);
                //return $this->sendApiError(__('api.'.$statusCode), ['error'=>config::get('Api_web_portal.status')[$statusCode], 'Status code' => $statusCode], $statusCode);
                return $this->sendApiError($error, $statusCode);
            }

        }catch (\Exception $exception) {
            // If anny other unknown error occurs
            $statusCode = $response_status_code::HTTP_NOT_FOUND; // or $statusCode = 404
            $error = __('api.'.$statusCode);
            $error = [
                'Error' => 'System',
                'System message' => $exception->getMessage()
            ];
                return $this->sendApiError($error, $statusCode);
        }
    }

    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email:rfc,dns|unique:users,email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()){
                $error = [
                  'Title' => 'Validation Error',
                  'Validation message' => $validator->errors()
                ];
                return $this->sendApiError( $error, 512);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $success = [
                'name' => $user->name,
                'email' => $user->email,
            ];

            return $this->sendApiResponse($success, 200);
        }catch (\Exception $exception) {
            return $this->sendApiError(['System Error' => $exception->getMessage()], 512);
        }

    }
}
