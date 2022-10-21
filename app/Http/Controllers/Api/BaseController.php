<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /** This is the success response method
     * This method can also help to save or insert Success responses into activities table or/and write into log file
     * @param $result
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendApiResponse($result, $statusCode = 200)
    {
        try {
            $response = [
                'message' => $result,
                'status' => 'success',
                'code' => $statusCode,
            ];
            return response()->json($response, $statusCode);
        }catch (\Exception $exception){
            return $this->sendApiError(['Error' => $exception->getMessage()], 404);
        }

    }

    /**
     * This is when any error occurs.
     * This method can also help to save or insert errors responses into activities table or/and write into log file
     * @param $error
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendApiError($error = [], $code = 404)
    {
        try {
            $response = [
                'message' => $error,
                'status' => 'Error',
                'code' => $code
            ];
            return response()->json($response, $code);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 404);
        }

    }
}
