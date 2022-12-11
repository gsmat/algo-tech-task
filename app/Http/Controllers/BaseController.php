<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use JsonException;

class BaseController extends Controller
{
    /**
     * @param bool|string|int $message
     * @param array|string|null $data
     * @param string|null $dataKeyword
     * @param int $status
     * @return JsonResponse
     * @throws JsonException
     */

    public static function responseJson(bool|string|int $message, array|null|string $data, string|null $dataKeyword, int $status): JsonResponse
    {
        if ($data === null) {
            return response()->json([
                'message' => $message,
            ], $status);
        }
        if (is_string($data)) {
            $data = json_decode($data, false, 512, JSON_THROW_ON_ERROR);
        }
        return response()->json([
            'message' => $message,
            $dataKeyword => $data
        ], $status);

    }
}
