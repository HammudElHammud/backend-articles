<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait APIResponser
{

    /**
     * @param null $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($message = null, $data = [], $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * @param null $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function errorResponse($message = null, $data = [], $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $code);
    }

}
