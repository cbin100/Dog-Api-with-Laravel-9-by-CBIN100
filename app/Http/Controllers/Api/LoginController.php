<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;
class LoginController extends BaseController
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = Auth::user();
            $success['token'] =  $authUser->createToken('WatchibaApiTest')->plainTextToken;
            $success['name'] =  $authUser->name;

            return $this->sendApiResponse($success, 'User logged in', 200);
        }
        else{
            return $this->sendApiError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

}
