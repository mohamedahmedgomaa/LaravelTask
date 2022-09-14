<?php


use Illuminate\Http\JsonResponse;

/**
 * @param $status
 * @param $message
 * @param null $data
 * @return JsonResponse
 */
function responseJson($status, $message, $data=null): JsonResponse
{
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
    return response()->json($response, $status);
}

