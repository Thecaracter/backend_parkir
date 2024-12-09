<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Success Response
     */
    protected function success($message = '', $data = null, $code = 200)
    {
        return response()->json([
            'statuscode' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error Response
     */
    protected function error($message = '', $code = 400)
    {
        return response()->json([
            'statuscode' => $code,
            'message' => $message,
            'data' => null
        ], $code);
    }

    /**
     * Common Success Responses
     */
    protected function okResponse($data = null, $message = 'Success')
    {
        return $this->success($message, $data, 200);
    }

    protected function createdResponse($data = null, $message = 'Created Successfully')
    {
        return $this->success($message, $data, 201);
    }

    /**
     * Common Error Responses
     */
    protected function badRequestResponse($message = 'Bad Request')
    {
        return $this->error($message, 400);
    }

    protected function unauthorizedResponse($message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }

    protected function notFoundResponse($message = 'Not Found')
    {
        return $this->error($message, 404);
    }

    protected function validationErrorResponse($message = 'Validation Error')
    {
        return $this->error($message, 422);
    }

    protected function serverErrorResponse($message = 'Server Error')
    {
        return $this->error($message, 500);
    }
}