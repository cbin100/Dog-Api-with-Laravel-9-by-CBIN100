<?php

namespace App\Exceptions;

use Exception;

class ApiExceptionHandler extends Exception
{
    public function report()
    {

    }

    public function render($request, Exception $exception)
    {
        /*  is this exception? */

        if ( !empty($exception) ) {

            // set default error message
            $response = [
                'error' => 'Sorry, can not execute your request.'
            ];

            // If debug mode is enabled
            if (config('app.debug')) {
                // Add the exception class name, message and stack trace to response
                $response['exception'] = get_class($exception); // Reflection might be better here
                $response['message'] = $exception->getMessage();
                $response['trace'] = $exception->getTrace();
            }

            $status = 400;

            // get correct status code

            // is this validation exception
            if($exception instanceof ValidationException){

                return $this->convertValidationExceptionToResponse($exception, $request);

                // is it authentication exception
            }else if($exception instanceof AuthenticationException){

                $status = 401;

                $response['error'] = 'Can not finish authentication!';

                //is it DB exception
            }else if($exception instanceof \PDOException){

                $status = 500;

                $response['error'] = 'Can not finish your query request!';

                // is it http exception (this can give us status code)
            }else if ($request->is('api/*')) {
                //$baseController = new BaseController; // Object BaseController so that we can access method for errors
                //$statusCode = 404;
                $status = $exception->getStatusCode();
                $response['error'] = 'URL Not fund!';
                //return $baseController->sendApiError(__('api.'.$statusCode), ['error'=> __('api.'.$statusCode)], $statusCode);
            }else if($this->isHttpException($exception)){

                $status = $exception->getStatusCode();

                $response['error'] = 'Request error!';

            }else{

                // for all others check do we have method getStatusCode and try to get it
                // otherwise, set the status to 400
                $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;
            }
            //return false;
            return response()->json($response,$status);
        }
    }
}
