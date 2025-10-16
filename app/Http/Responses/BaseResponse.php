<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class BaseResponse
{
    public static function success($data = []): JsonResponse
    {

        return self::makeResponse('success', $data, 200);
    }

    public static function error($message, $status): JsonResponse
    {
        return self::makeResponse($message, [], $status);
    }

    private static function makeResponse($message, $data, $status)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'api_version' => config('app.version'),
        ], $status);
    }
}
