<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\ApiExceptions\ApiBaseException;

class ApiResponse
{
    /**
     * Handle Api response and exceptions
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $exception = $response->exception;

        // Handle exception
        if (!empty($exception)) {
            // Handle Api standard exceptions
            if ($exception instanceof ApiBaseException) {
                $statusCode = $exception->getCode();
                $data = [
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                    'data' => $exception->getData(),
                ];
            } // Handle other exceptions
            else {
                $statusCode = 500;
                $data = [
                    'status' => 'error',
                    // Do not show exact error on production
                    'message' => \App::environment('local', 'dev') ? $exception->getMessage() : 'Internal server error',
                    'data' => [],
                ];
            }
        } // In case of no exception, set standard response
        else {
            $statusCode = $response->getStatusCode();
            $originalResponse = $response->original;

            // Allow custom message and data.
            $responseMessage = isset($originalResponse['message']) ? $originalResponse['message'] : '';
            $responseData = isset($originalResponse['data']) ? $originalResponse['data'] : $originalResponse;

            // If response data is null, set it to empty array
            if (is_null($responseData)) $responseData = [];

            // In case there is only one element in the array and it is message, empty response data
            if (isset($originalResponse['message']) && count($originalResponse) == 1) $responseData = [];

            $data = [
                'status' => 'success',
                'message' => $responseMessage,
                'data' => $responseData
            ];
        }

        $response->setContent(json_encode($data))->setStatusCode($statusCode);

        return $response;
    }
}