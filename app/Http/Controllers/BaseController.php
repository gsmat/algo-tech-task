<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * @param bool|string|int $message
     * @param array|string|null $data
     * @param string $dataKeyword
     * @param int $status
     * @return JsonResponse
     */

    public static function responseJson(bool|string|int $message, array|null|string $data, string $dataKeyword, int $status): JsonResponse
    {
        return response()->json([
            'message' => $message,
            $dataKeyword => json_decode($data)
        ], $status);
    }
}
